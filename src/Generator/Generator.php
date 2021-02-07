<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Generator extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?GeneratorInterface {

    $candidate = $formulaToAnything->formula(
      $formula,
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
