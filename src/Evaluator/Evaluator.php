<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\UtilBase;

final class Evaluator extends UtilBase {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\EvaluatorInterface|null
   */
  public static function fromSchema(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?EvaluatorInterface {

    $candidate = $schemaToAnything->schema(
      $schema,
      EvaluatorInterface::class);

    if ($candidate instanceof EvaluatorInterface) {
      return $candidate;
    }

    if (null === $candidate) {
      return null;
    }

    throw new \RuntimeException("Expected an EvaluatorInterface object or NULL.");
  }

}
