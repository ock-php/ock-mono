<?php

declare(strict_types=1);

namespace Donquixote\Ock\AnnotatedFormula\IA;

class AnnotatedFormulaIA_Conditional implements AnnotatedFormulaIAInterface {

  private AnnotatedFormulaIAInterface $decorated;

  private array $conditions;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\AnnotatedFormula\IA\AnnotatedFormulaIAInterface $decorated
   * @param array $conditions
   *   Format: $[$key] = $expected_value.
   */
  public function __construct(AnnotatedFormulaIAInterface $decorated, array $conditions) {
    $this->decorated = $decorated;
    $this->conditions = $conditions;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->decorated as $annotated_formula) {
      $info = $annotated_formula->getInfo();
      foreach ($this->conditions as $key => $expected) {
        if ($info[$key] ?? NULL !== $expected) {
          continue 2;
        }
      }
      yield $annotated_formula;
    }
  }

}
