<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\TwoStep\CfSchema_TwoStepInterface;
use Donquixote\Cf\Schema\TwoStepVal\CfSchema_TwoStepValInterface;
use Donquixote\Cf\Zoo\V2V\TwoStep\V2V_TwoStepInterface;

class CfSchema_EntityDisplay_Layout implements CfSchema_TwoStepInterface, CfSchema_TwoStepValInterface {

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
  public function getFirstStepSchema(): CfSchemaInterface {
    // TODO: Implement getFirstStepSchema() method.
  }

  /**
   * @inheritDoc
   */
  public function firstStepValueGetSecondStepSchema($firstStepValue): ?CfSchemaInterface {
    // TODO: Implement firstStepValueGetSecondStepSchema() method.
  }

  /**
   * @inheritDoc
   */
  public function getDecorated(): CfSchemaInterface {
    // TODO: Implement getDecorated() method.
  }

  /**
   * @inheritDoc
   */
  public function getV2V(): V2V_TwoStepInterface {
    // TODO: Implement getV2V() method.
  }

}
