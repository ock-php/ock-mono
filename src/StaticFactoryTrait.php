<?php
declare(strict_types=1);

namespace Donquixote\OCUI;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Generator\Generator;
use Donquixote\OCUI\Generator\GeneratorInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Summarizer\Summarizer;
use Donquixote\OCUI\Summarizer\SummarizerInterface;

trait StaticFactoryTrait {

  /**
   * @var CfSchemaInterface|null
   */
  private static $schema;

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface|null
   */
  public static function schema(): CfSchemaInterface {
    return self::$schema
      ?? self::$schema = static::createSchema();
  }

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  abstract protected static function createSchema(): CfSchemaInterface;

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
