<?php
declare(strict_types=1);

namespace Donquixote\OCUI;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Generator\Generator;
use Donquixote\OCUI\Generator\GeneratorInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Summarizer\Summarizer;
use Donquixote\OCUI\Summarizer\SummarizerInterface;

trait StaticFactoryTrait {

  /**
   * @var FormulaInterface|null
   */
  private static $formula;

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  public static function formula(): FormulaInterface {
    return self::$formula
      ?? self::$formula = static::createFormula();
  }

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  abstract protected static function createFormula(): FormulaInterface;

  /**
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   */
  public static function evaluator(FormulaToAnythingInterface $formulaToAnything): GeneratorInterface {

    return Generator::fromFormula(
      static::formula(),
      $formulaToAnything);
  }

  /**
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   */
  public static function summarizer(FormulaToAnythingInterface $formulaToAnything): ?SummarizerInterface {

    return Summarizer::fromFormula(
      static::formula(),
      $formulaToAnything);
  }

}
