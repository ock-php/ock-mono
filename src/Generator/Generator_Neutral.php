<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;

class Generator_Neutral extends Generator_DecoratorBase {

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_SkipEvaluatorInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?GeneratorInterface {

    return Generator::fromSchema($schema->getDecorated(), $schemaToAnything);
  }
}
