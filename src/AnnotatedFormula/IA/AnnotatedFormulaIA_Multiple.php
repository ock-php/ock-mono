<?php

declare(strict_types=1);

namespace Donquixote\Ock\AnnotatedFormula\IA;

class AnnotatedFormulaIA_Multiple implements AnnotatedFormulaIAInterface {

  /**
   * @var \Donquixote\Ock\AnnotatedFormula\IA\AnnotatedFormulaIAInterface[]
   */
  private $ias;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\AnnotatedFormula\IA\AnnotatedFormulaIAInterface[] $ias
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
