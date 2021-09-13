<?php

declare(strict_types=1);

namespace Donquixote\Ock\AnnotatedFormula\IA;

interface AnnotatedFormulaIAInterface extends \IteratorAggregate {

  /**
   * Finds annotated formulas.
   *
   * @return iterable<\Donquixote\Ock\AnnotatedFormula\AnnotatedFormulaInterface>
   */
  public function getIterator(): iterable;

}
