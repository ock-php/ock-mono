<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;

class Generator_Neutral extends Generator_DecoratorBase {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(Formula_SkipEvaluatorInterface $formula, NurseryInterface $formulaToAnything): ?GeneratorInterface {

    return Generator::fromFormula($formula->getDecorated(), $formulaToAnything);
  }
}
