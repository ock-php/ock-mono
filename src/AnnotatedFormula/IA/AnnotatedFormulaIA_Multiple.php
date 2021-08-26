<?php

declare(strict_types=1);

namespace Donquixote\ObCK\AnnotatedFormula\IA;

class AnnotatedFormulaIA_Multiple implements AnnotatedFormulaIAInterface {

  /**
   * @var \Donquixote\ObCK\AnnotatedFormula\IA\AnnotatedFormulaIAInterface[]
   */
  private $ias;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\AnnotatedFormula\IA\AnnotatedFormulaIAInterface[] $ias
   */
  public function __construct(array $ias) {
    $this->ias = $ias;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->ias as $ia) {
      yield from $ia;
    }
  }

}
