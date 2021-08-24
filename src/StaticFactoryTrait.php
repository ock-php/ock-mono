<?php
declare(strict_types=1);

namespace Donquixote\ObCK;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Generator\Generator;
use Donquixote\ObCK\Generator\GeneratorInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Summarizer\Summarizer;
use Donquixote\ObCK\Summarizer\SummarizerInterface;

trait StaticFactoryTrait {

  /**
   * @var FormulaInterface|null
   */
  private static $formula;

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public static function formula(): FormulaInterface {
    return self::$formula
      ?? self::$formula = static::createFormula();
  }

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  abstract protected static function createFormula(): FormulaInterface;

  /**
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Generator\GeneratorInterface|null
   */
  public static function evaluator(FormulaToAnythingInterface $formulaToAnything): GeneratorInterface {

    return Generator::fromFormula(
      static::formula(),
      $formulaToAnything);
  }

  /**
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Summarizer\SummarizerInterface|null
   */
  public static function summarizer(FormulaToAnythingInterface $formulaToAnything): ?SummarizerInterface {

    return Summarizer::fromFormula(
      static::formula(),
      $formulaToAnything);
  }

}
