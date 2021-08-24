<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\TwoStep;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface Formula_TwoStepInterface extends FormulaInterface {

  /**
   * @return string
   */
  public function getFirstStepKey(): string;

  /**
   * @return string
   */
  public function getSecondStepKey(): string;

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getFirstStepFormula(): FormulaInterface;

  /**
   * @param mixed $firstStepValue
   *   Value from the first step of configuration.
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   *
   * @todo return NULL or throw exception?
   */
  public function firstStepValueGetSecondStepFormula($firstStepValue): ?FormulaInterface;

}
