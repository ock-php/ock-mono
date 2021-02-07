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
  private static $schema;

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  public static function schema(): FormulaInterface {
    return self::$schema
      ?? self::$schema = static::createFormula();
  }

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  abstract protected static function createFormula(): FormulaInterface;

  /**
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   */
  public static function evaluator(FormulaToAnythingInterface $schemaToAnything): GeneratorInterface {

    return Generator::fromFormula(
      static::schema(),
      $schemaToAnything);
  }

  /**
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   */
  public static function summarizer(FormulaToAnythingInterface $schemaToAnything): ?SummarizerInterface {

    return Summarizer::fromFormula(
      static::schema(),
      $schemaToAnything);
  }

}
