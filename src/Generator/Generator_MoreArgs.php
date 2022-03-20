<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Attribute\Plugin\ActAsIncarnator;
use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgsInterface;
use Donquixote\Ock\Formula\MoreArgsVal\Formula_MoreArgsValInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

class Generator_MoreArgs extends Generator_DecoratorBase {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface[]
   */
  private $moreGenerators;

  /**
   * @var int|string
   */
  private $specialKey;

  /**
   * @var \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @var string[]|null
   */
  private $commonValuesPhp;

  /**
   * @param \Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgsInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  #[OckIncarnator]
  public static function createFromMoreArgsFormula(Formula_MoreArgsInterface $formula, IncarnatorInterface $incarnator): ?Generator_MoreArgs {
    return self::create($formula, new V2V_Group_Trivial(), $incarnator);
  }

  /**
   * @param \Donquixote\Ock\Formula\MoreArgsVal\Formula_MoreArgsValInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  #[OckIncarnator]
  public static function createFromMoreArgsValFormula(
    Formula_MoreArgsValInterface $formula,
    IncarnatorInterface $incarnator
  ): ?Generator_MoreArgs {
    return self::create(
      $formula->getDecorated(),
      $formula->getV2V(),
      $incarnator);
  }

  /**
   * @param \Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgsInterface $moreArgsFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(
    Formula_MoreArgsInterface $moreArgsFormula,
    V2V_GroupInterface $v2v,
    IncarnatorInterface $incarnator
  ): ?Generator_MoreArgs {

    $decoratedGenerator = Generator::fromFormula(
      $moreArgsFormula->getDecorated(),
      $incarnator);

    if (NULL === $decoratedGenerator) {
      return NULL;
    }

    $moreGenerators = [];
    foreach ($moreArgsFormula->getMoreArgs() as $k => $itemFormula) {
      $itemGenerator = Generator::fromFormula($itemFormula, $incarnator);
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
   * @param string|int $specialKey
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(
    GeneratorInterface $decorated,
    array $moreGenerators,
    $specialKey,
    V2V_GroupInterface $v2v
  ) {
    parent::__construct($decorated);
    $this->moreGenerators = $moreGenerators;
    $this->specialKey = $specialKey;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $valuesPhp = $this->getCommonValuesPhp();
    $valuesPhp[$this->specialKey] = parent::confGetPhp($conf);

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
    $commonValuesPhp[$this->specialKey] = NULL;
    foreach ($this->moreGenerators as $k => $evaluator) {
      $commonValuesPhp[$k] = $evaluator->confGetPhp(NULL);
    }

    return $commonValuesPhp;
  }

}
