<?php
declare(strict_types=1);

namespace Donquixote\OCUI;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Generator\Generator;
use Donquixote\OCUI\Generator\GeneratorInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
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
      ?? self::$schema = static::createSchema();
  }

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  abstract protected static function createSchema(): FormulaInterface;

  /**
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   */
  public static function evaluator(SchemaToAnythingInterface $schemaToAnything): GeneratorInterface {

    return Generator::fromSchema(
      static::schema(),
      $schemaToAnything);
  }

  /**
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   */
  public static function summarizer(SchemaToAnythingInterface $schemaToAnything): ?SummarizerInterface {

    return Summarizer::fromSchema(
      static::schema(),
      $schemaToAnything);
  }

}
