<?php

declare(strict_types=1);

namespace Donquixote\Ock\Decorator;

use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\Generator\Generator;
use Donquixote\Ock\Generator\Generator_Group;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\PhpUtil;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

class Decorator_Group implements DecoratorInterface {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface[]
   */
  private $itemGenerators;

  /**
   * @var \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromGroupFormula(
    Formula_GroupInterface $formula,
    IncarnatorInterface $incarnator
  ): ?Generator_Group {
    return self::create(
      $formula,
      new V2V_Group_Trivial(),
      $incarnator);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromGroupValFormula(
    Formula_GroupValInterface $formula,
    IncarnatorInterface $incarnator
  ): ?Generator_Group {
    return self::create(
      $formula->getDecorated(),
      $formula->getV2V(),
      $incarnator);
  }

  /**
   * Static factory.
   *
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $groupFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_GroupInterface $groupFormula, V2V_GroupInterface $v2v, IncarnatorInterface $incarnator): ?Generator_Group {

    $itemGenerators = [];
    foreach ($groupFormula->getItemFormulas() as $k => $itemFormula) {
      $itemGenerator = Generator::fromFormula($itemFormula, $incarnator);
      if (NULL === $itemGenerator) {
        return NULL;
      }
      $itemGenerators[$k] = $itemGenerator;
    }

    return new self($itemGenerators, $v2v);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Generator\GeneratorInterface[] $itemGenerators
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(array $itemGenerators, V2V_GroupInterface $v2v) {
    $this->itemGenerators = $itemGenerators;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confDecoratePhp($conf, string $php): string {

    if (!\is_array($conf)) {
      // If all values are optional, this might still work.
      return PhpUtil::expectedConfigButFound('Configuration must be an array.', $conf);
    }

    $phpStatements = [];
    foreach ($this->itemGenerators as $key => $itemGenerator) {

      $itemConf = $conf[$key] ?? null;

      $phpStatements[$key] = $itemGenerator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }

}
