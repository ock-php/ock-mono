<?php

declare(strict_types=1);

namespace Donquixote\ObCK\AnnotatedFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface AnnotatedFormulaInterface {

  /**
   * Gets the type.
   *
   * @return string
   *   The types, typically an interface name.
   */
  public function getType(): string;

  /**
   * Gets data from the annotation.
   *
   * @return array
   *   Data from the annotations.
   */
  public function getInfo(): array;

  /**
   * Gets the formula.
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getFormula(): FormulaInterface;

}
