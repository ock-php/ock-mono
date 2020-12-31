<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class Generator_Neutral extends Generator_DecoratorBase {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_SkipEvaluatorInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?GeneratorInterface {

    return Generator::fromSchema($schema->getDecorated(), $schemaToAnything);
  }
}
