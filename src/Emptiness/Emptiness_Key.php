<?php
declare(strict_types=1);

namespace Donquixote\Cf\Emptiness;

class Emptiness_Key implements EmptinessInterface {

  /**
   * @var string
   */
  private $key;

  /**
   * @param string $key
   */
  public function __construct(string $key) {
    $this->key = $key;
  }

  /**
   * {@inheritdoc}
   */
  public function confIsEmpty($conf): bool {
    return !isset($conf[$this->key]) || '' === $conf[$this->key];
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyConf() {
    return NULL;
  }
}
