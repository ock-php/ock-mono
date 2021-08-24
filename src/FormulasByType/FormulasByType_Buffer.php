<?php

namespace Donquixote\ObCK\FormulasByType;

class FormulasByType_Buffer implements FormulasByTypeInterface {

  /**
   * @var \Donquixote\ObCK\FormulasByType\FormulasByTypeInterface
   */
  private $decorated;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\FormulasByType\FormulasByTypeInterface $decorated
   */
  public function __construct(FormulasByTypeInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormulasByType(): array {
    return $this->decorated->getFormulasByType();
  }

}
