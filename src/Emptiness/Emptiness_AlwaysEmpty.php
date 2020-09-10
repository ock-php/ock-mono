<?php
declare(strict_types=1);

namespace Donquixote\Cf\Emptiness;

class Emptiness_AlwaysEmpty implements EmptinessInterface {

  /**
   * @var mixed|null
   */
  private $emptyConf;

  /**
   * @param mixed $emptyConf
   */
  public function __construct($emptyConf = NULL) {
    $this->emptyConf = $emptyConf;
  }

  /**
   * {@inheritdoc}
   */
  public function confIsEmpty($conf): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyConf() {
    return $this->emptyConf;
  }
}
