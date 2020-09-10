<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class Evaluator_Neutral extends Evaluator_DecoratorBase {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\EvaluatorInterface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_SkipEvaluatorInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?EvaluatorInterface {

    return Evaluator::fromSchema($schema->getDecorated(), $schemaToAnything);
  }
}
