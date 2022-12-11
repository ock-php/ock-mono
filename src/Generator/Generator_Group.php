<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Adaptism\Util\MessageUtil;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

class Generator_Group implements GeneratorInterface {

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function createFromGroupFormula(
    #[Adaptee] Formula_GroupInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): ?self {
    return self::create($formula, new V2V_Group_Trivial(), $universalAdapter);
  }

  /**
   * @param \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function createFromGroupValFormula(
    #[Adaptee] Formula_GroupValInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): ?self {
    return self::create($formula->getDecorated(), $formula->getV2V(), $universalAdapter);
  }

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $groupFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public static function create(Formula_GroupInterface $groupFormula, V2V_GroupInterface $v2v, UniversalAdapterInterface $universalAdapter): ?self {

    $itemGenerators = [];
    foreach ($groupFormula->getItemFormulas() as $k => $itemFormula) {
      $itemGenerator = Generator::fromFormula($itemFormula, $universalAdapter);
      $itemGenerators[$k] = $itemGenerator;
    }

    return new self($itemGenerators, $v2v);
  }

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface[] $itemGenerators
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(
    private readonly array $itemGenerators,
    private readonly V2V_GroupInterface $v2v,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetPhp(mixed $conf): string {

    if ($conf === null) {
      $conf = [];
    }
    elseif (!\is_array($conf)) {
      if ($this->itemGenerators) {
        // At least one configurable item exists in the group.
        throw new GeneratorException_IncompatibleConfiguration(sprintf(
          'Expected an array, found %s.',
          MessageUtil::formatValue($conf),
        ));
      }
    }

    $phpStatements = [];
    foreach ($this->itemGenerators as $key => $itemGenerator) {
      // @todo Complain if setting is missing, instead of assuming NULL.
      $itemConf = $conf[$key] ?? NULL;
      $phpStatements[$key] = $itemGenerator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }

}
