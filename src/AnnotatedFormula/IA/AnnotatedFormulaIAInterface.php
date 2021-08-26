<?php

declare(strict_types=1);

namespace Donquixote\ObCK\AnnotatedFormula\IA;

interface AnnotatedFormulaIAInterface extends \IteratorAggregate {

  /**
   * Finds annotated formulas.
   *
   * @return iterable<\Donquixote\ObCK\AnnotatedFormula\AnnotatedFormulaInterface>
   */
  public function getIterator(): iterable;

}
