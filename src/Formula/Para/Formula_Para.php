<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Para;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class Formula_Para implements Formula_ParaInterface {

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private $paraFormula;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $paraFormula
   */
  public function __construct(FormulaInterface $decorated, FormulaInterface $paraFormula) {
    $this->decorated = $decorated;
    $this->paraFormula = $paraFormula;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getParaFormula(): FormulaInterface {
    return $this->paraFormula;
  }
}
