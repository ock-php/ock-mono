<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\MoreArgs\Formula_MoreArgsInterface;
use Donquixote\ObCK\Formula\MoreArgsVal\Formula_MoreArgsValInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\V2V\Group\V2V_Group_Trivial;
use Donquixote\ObCK\V2V\Group\V2V_GroupInterface;

class Generator_MoreArgs extends Generator_DecoratorBase {

  /**
   * @var \Donquixote\ObCK\Generator\GeneratorInterface[]
   */
  private $moreGenerators;

  /**
   * @var int|string
   */
  private $specialKey;

  /**
   * @var \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @var string[]|null
   */
  private $commonValuesPhp;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\MoreArgs\Formula_MoreArgsInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function createFromMoreArgsFormula(Formula_MoreArgsInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?Generator_MoreArgs {
    return self::create($formula, new V2V_Group_Trivial(), $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\MoreArgsVal\Formula_MoreArgsValInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function createFromMoreArgsValFormula(
    Formula_MoreArgsValInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?Generator_MoreArgs {
    return self::create(
      $formula->getDecorated(),
      $formula->getV2V(),
      $formulaToAnything);
  }

  /**
   * @param \Donquixote\ObCK\Formula\MoreArgs\Formula_MoreArgsInterface $moreArgsFormula
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_MoreArgsInterface $moreArgsFormula,
    V2V_GroupInterface $v2v,
    FormulaToAnythingInterface $formulaToAnything
  ): ?Generator_MoreArgs {

    $decoratedGenerator = Generator::fromFormula(
      $moreArgsFormula->getDecorated(),
      $formulaToAnything);

    if (NULL === $decoratedGenerator) {
      return NULL;
    }

    $moreGenerators = [];
    foreach ($moreArgsFormula->getMoreArgs() as $k => $itemFormula) {
      $itemGenerator = Generator::fromFormula($itemFormula, $formulaToAnything);
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
   * @param \Donquixote\ObCK\Generator\GeneratorInterface $decorated
   * @param \Donquixote\ObCK\Generator\GeneratorInterface[] $moreGenerators
   * @param string|int $specialKey
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $v2v
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
   */
  private function getCommonValuesPhp(): array {
    return $this->commonValuesPhp
      ?? $this->commonValuesPhp = $this->buildCommonValuesPhp();
  }

  /**
   * @return string[]
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
