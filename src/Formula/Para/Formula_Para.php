<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Para;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

class Formula_Para implements Formula_ParaInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private $paraFormula;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $paraFormula
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
