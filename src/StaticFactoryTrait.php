<?php
declare(strict_types=1);

namespace Donquixote\Cf;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Generator\Generator;
use Donquixote\Cf\Generator\GeneratorInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Summarizer\Summarizer;
use Donquixote\Cf\Summarizer\SummarizerInterface;

trait StaticFactoryTrait {

  /**
   * @var CfSchemaInterface|null
   */
  private static $schema;

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  public static function schema(): CfSchemaInterface {
    return self::$schema
      ?? self::$schema = static::createSchema();
  }

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  abstract protected static function createSchema(): CfSchemaInterface;

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Generator\GeneratorInterface|null
   */
  public static function evaluator(SchemaToAnythingInterface $schemaToAnything): GeneratorInterface {

    return Generator::fromSchema(
      static::schema(),
      $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface|null
   */
  public static function summarizer(SchemaToAnythingInterface $schemaToAnything): ?SummarizerInterface {

    return Summarizer::fromSchema(
      static::schema(),
      $schemaToAnything);
  }

}
