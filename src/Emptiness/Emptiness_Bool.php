<?php
declare(strict_types=1);

namespace Donquixote\Cf\Emptiness;

class Emptiness_Bool implements EmptinessInterface {

  /**
   * {@inheritdoc}
   */
  public function confIsEmpty($conf): bool {
    return empty($conf);
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyConf() {
    return FALSE;
  }
}
