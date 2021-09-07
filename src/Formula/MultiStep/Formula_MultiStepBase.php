<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\MultiStep;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

abstract class Formula_MultiStepBase implements Formula_MultiStepInterface {

  private string $key;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private FormulaInterface $formula;

  /**
   * Constructor.
   *
   * @param string $key
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   */
  public function __construct(string $key, FormulaInterface $formula) {
    $this->key = $key;
    $this->formula = $formula;
  }

  public function getKey(): string {
    return $this->key;
  }

  public function getFormula(): FormulaInterface {
    return $this->formula;
  }

}
