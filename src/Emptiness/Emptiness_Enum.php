<?php
declare(strict_types=1);

namespace Donquixote\Cf\Emptiness;

class Emptiness_Enum implements EmptinessInterface {

  /**
   * {@inheritdoc}
   */
  public function confIsEmpty($conf): bool {
    return NULL === $conf || '' === $conf;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyConf() {
    return NULL;
  }
}
