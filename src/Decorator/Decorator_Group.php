<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Decorator;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Generator\Generator;
use Donquixote\ObCK\Generator\Generator_Group;
use Donquixote\ObCK\Util\PhpUtil;
use Donquixote\ObCK\V2V\Group\V2V_Group_Trivial;
use Donquixote\ObCK\V2V\Group\V2V_GroupInterface;

class Decorator_Group implements DecoratorInterface {

  /**
   * @var \Donquixote\ObCK\Generator\GeneratorInterface[]
   */
  private $itemGenerators;

  /**
   * @var \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromGroupFormula(
    Formula_GroupInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?Generator_Group {
    return self::create(
      $formula,
      new V2V_Group_Trivial(),
      $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromGroupValFormula(
    Formula_GroupValInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?Generator_Group {
    return self::create(
      $formula->getDecorated(),
      $formula->getV2V(),
      $formulaToAnything);
  }

  /**
   * Static factory.
   *
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $groupFormula
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupInterface $groupFormula, V2V_GroupInterface $v2v, FormulaToAnythingInterface $formulaToAnything): ?Generator_Group {

    $itemGenerators = [];
    foreach ($groupFormula->getItemFormulas() as $k => $itemFormula) {
      $itemGenerator = Generator::fromFormula($itemFormula, $formulaToAnything);
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
   * @param \Donquixote\ObCK\Generator\GeneratorInterface[] $itemGenerators
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $v2v
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
