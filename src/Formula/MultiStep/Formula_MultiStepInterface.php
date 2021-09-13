<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\MultiStep;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface Formula_MultiStepInterface extends FormulaInterface {

  /**
   * Gets the config key for the current step.
   *
   * @return string
   */
  public function getKey(): string;

  /**
   * Gets the formula for the current step.
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function getFormula(): FormulaInterface;

  /**
   * Immutable. Advances to the next step.
   *
   * @param mixed $conf
   *   Configuration for the current step.
   *
   * @return \Donquixote\Ock\Formula\MultiStep\Formula_MultiStepInterface|null
   *   Multi-step formula representing the next step.
   */
  public function next($conf): ?Formula_MultiStepInterface;

}
