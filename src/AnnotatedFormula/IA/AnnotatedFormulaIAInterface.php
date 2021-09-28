<?php

declare(strict_types=1);

namespace Donquixote\Ock\AnnotatedFormula\IA;

interface AnnotatedFormulaIAInterface extends \IteratorAggregate {

  /**
   * Finds annotated formulas.
   *
   * @return \Iterator<\Donquixote\Ock\AnnotatedFormula\AnnotatedFormulaInterface>
   */
  public function getIterator(): \Iterator;

}
