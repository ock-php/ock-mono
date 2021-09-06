<?php

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\TwoStep\Formula_TwoStepInterface;
use Donquixote\ObCK\Formula\TwoStepVal\Formula_TwoStepValInterface;
use Donquixote\ObCK\Zoo\V2V\TwoStep\V2V_TwoStepInterface;

class Formula_EntityDisplay_Layout implements Formula_TwoStepInterface, Formula_TwoStepValInterface {

  /**
   * @inheritDoc
   */
  public function getFirstStepKey(): string {
    // TODO: Implement getFirstStepKey() method.
  }

  /**
   * @inheritDoc
   */
  public function getSecondStepKey(): string {
    // TODO: Implement getSecondStepKey() method.
  }

  /**
   * @inheritDoc
   */
  public function getFirstStepFormula(): FormulaInterface {
    // TODO: Implement getFirstStepFormula() method.
  }

  /**
   * @inheritDoc
   */
  public function firstStepValueGetSecondStepFormula($firstStepValue): ?FormulaInterface {
    // TODO: Implement firstStepValueGetSecondStepFormula() method.
  }

  /**
   * @inheritDoc
   */
  public function getDecorated(): FormulaInterface {
    // TODO: Implement getDecorated() method.
  }

  /**
   * @inheritDoc
   */
  public function getV2V(): V2V_TwoStepInterface {
    // TODO: Implement getV2V() method.
  }

}
