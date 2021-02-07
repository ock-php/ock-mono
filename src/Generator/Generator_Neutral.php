<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class Generator_Neutral extends Generator_DecoratorBase {

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface $schema
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_SkipEvaluatorInterface $schema, FormulaToAnythingInterface $schemaToAnything): ?GeneratorInterface {

    return Generator::fromFormula($schema->getDecorated(), $schemaToAnything);
  }
}
