<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Para;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class Formula_Para implements Formula_ParaInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $paraFormula;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $paraFormula
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
