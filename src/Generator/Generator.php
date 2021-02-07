<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Generator extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   */
  public static function fromFormula(
    FormulaInterface $schema,
    FormulaToAnythingInterface $schemaToAnything
  ): ?GeneratorInterface {

    $candidate = $schemaToAnything->schema(
      $schema,
      GeneratorInterface::class);

    if ($candidate instanceof GeneratorInterface) {
      return $candidate;
    }

    if (null === $candidate) {
      return null;
    }

    throw new \RuntimeException("Expected an GeneratorInterface object or NULL.");
  }

}
