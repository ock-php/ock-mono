<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\MultiStep;

class Formula_MultiStep_End extends Formula_MultiStepBase {

  /**
   * {@inheritdoc}
   */
  public function next($conf): ?Formula_MultiStepInterface {
    return NULL;
  }

}
