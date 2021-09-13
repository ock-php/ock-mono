<?php
declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;
use Donquixote\Ock\Nursery\NurseryInterface;

class Generator_Neutral extends Generator_DecoratorBase {

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_SkipEvaluatorInterface $formula, NurseryInterface $formulaToAnything): ?GeneratorInterface {

    return Generator::fromFormula($formula->getDecorated(), $formulaToAnything);
  }
}
