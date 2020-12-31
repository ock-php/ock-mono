<?php
declare(strict_types=1);

namespace Donquixote\Cf;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Evaluator\Evaluator;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
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
   * @return \Donquixote\Cf\Evaluator\EvaluatorInterface|null
   */
  public static function evaluator(SchemaToAnythingInterface $schemaToAnything): EvaluatorInterface {

    return Evaluator::fromSchema(
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
