<?php

declare(strict_types=1);

namespace Donquixote\ObCK\AnnotatedFormula\IA;

class AnnotatedFormulaIA_Buffer implements AnnotatedFormulaIAInterface {

  /**
   * @var \Donquixote\ObCK\AnnotatedFormula\IA\AnnotatedFormulaIAInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\AnnotatedFormula\AnnotatedFormulaInterface|null
   */
  private $buffer;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\AnnotatedFormula\IA\AnnotatedFormulaIAInterface $decorated
   */
  public function __construct(AnnotatedFormulaIAInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    if ($this->buffer !== NULL) {
      yield from $this->buffer;
    }
    $annotated_formulas = [];
    foreach ($this->decorated as $annotated_formula) {
      $annotated_formulas[] = $annotated_formula;
      yield $annotated_formula;
    }
    $this->buffer = $annotated_formulas;
  }

}
