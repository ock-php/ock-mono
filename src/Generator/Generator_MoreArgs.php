<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgsInterface;
use Donquixote\Ock\Formula\MoreArgsVal\Formula_MoreArgsValInterface;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

class Generator_MoreArgs implements GeneratorInterface {

  /**
   * @var string[]|null
   */
  private ?array $commonValuesPhp = null;

  /**
   * @param \Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgsInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function createFromMoreArgsFormula(
    #[Adaptee] Formula_MoreArgsInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): ?self {
    return self::create(
      $formula,
      new V2V_Group_Trivial(),
      $universalAdapter,
    );
  }

  /**
   * @param \Donquixote\Ock\Formula\MoreArgsVal\Formula_MoreArgsValInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function createFromMoreArgsValFormula(
    #[Adaptee] Formula_MoreArgsValInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter
  ): ?self {
    return self::create(
      $formula->getDecorated(),
      $formula->getV2V(),
      $universalAdapter,
    );
  }

  /**
   * @param \Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgsInterface $moreArgsFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public static function create(
    Formula_MoreArgsInterface $moreArgsFormula,
    V2V_GroupInterface $v2v,
    UniversalAdapterInterface $universalAdapter
  ): ?Generator_MoreArgs {

    $decoratedGenerator = Generator::fromFormula(
      $moreArgsFormula->getDecorated(),
      $universalAdapter);

    if (NULL === $decoratedGenerator) {
      return NULL;
    }

    $moreGenerators = [];
    foreach ($moreArgsFormula->getMoreArgs() as $k => $itemFormula) {
      $itemGenerator = Generator::fromFormula($itemFormula, $universalAdapter);
      if (NULL === $itemGenerator) {
        return NULL;
      }
      $moreGenerators[$k] = $itemGenerator;
    }

    return new self(
      $decoratedGenerator,
      $moreGenerators,
      $moreArgsFormula->getSpecialKey(),
      $v2v);
  }

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorated
   * @param \Donquixote\Ock\Generator\GeneratorInterface[] $moreGenerators
   *   Generators that accept NULL as configuration.
   * @param string|int $specialKey
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(
    private GeneratorInterface $decorated,
    private array $moreGenerators,
    private string|int $specialKey,
    private V2V_GroupInterface $v2v
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $valuesPhp = $this->getCommonValuesPhp();
    $valuesPhp[$this->specialKey] = $this->decorated->confGetPhp($conf);

    return $this->v2v->itemsPhpGetPhp($valuesPhp);
  }

  /**
   * @return string[]
   *
   * @throws \Donquixote\Ock\Exception\GeneratorException
   */
  private function getCommonValuesPhp(): array {
    return $this->commonValuesPhp
      ??= $this->buildCommonValuesPhp();
  }

  /**
   * @return string[]
   *
   * @throws \Donquixote\Ock\Exception\GeneratorException
   */
  private function buildCommonValuesPhp(): array {

    $commonValuesPhp = [];
    $commonValuesPhp[$this->specialKey] = 'NULL';
    foreach ($this->moreGenerators as $k => $generator) {
      $commonValuesPhp[$k] = $generator->confGetPhp(NULL);
    }

    return $commonValuesPhp;
  }

}
