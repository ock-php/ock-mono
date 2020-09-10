<?php
declare(strict_types=1);

namespace Donquixote\Cf\Emptiness;

class Emptiness_NeverEmpty implements EmptinessInterface {

  /**
   * {@inheritdoc}
   */
  public function confIsEmpty($conf): bool {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyConf() {
    throw new \Exception('Never empty.');
  }
}
