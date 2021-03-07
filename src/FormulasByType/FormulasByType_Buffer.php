<?php

namespace Donquixote\OCUI\FormulasByType;

class FormulasByType_Buffer implements FormulasByTypeInterface {

  /**
   * @var \Donquixote\OCUI\FormulasByType\FormulasByTypeInterface
   */
  private $decorated;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\FormulasByType\FormulasByTypeInterface $decorated
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
