<?php
declare(strict_types=1);

namespace Donquixote\Cf\Emptiness;

class Emptiness_Whitelist implements EmptinessInterface {

  /**
   * @var mixed
   */
  private $defaultEmptyConf;

  /**
   * @var mixed[]
   */
  private $otherEmptyConfigurations;

  /**
   * @param mixed $defaultEmptyConf
   * @param mixed[] $otherEmptyConfigurations
   */
  public function __construct($defaultEmptyConf, array $otherEmptyConfigurations = []) {
    $this->defaultEmptyConf = $defaultEmptyConf;
    $this->otherEmptyConfigurations = $otherEmptyConfigurations;
  }

  /**
   * {@inheritdoc}
   */
  public function confIsEmpty($conf): bool {
    if ($conf === $this->defaultEmptyConf) {
      return TRUE;
    }
    // in_array() does not work here because it uses == instead of ===.
    foreach ($this->otherEmptyConfigurations as $emptyConf) {
      if ($conf === $emptyConf) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyConf() {
    return $this->defaultEmptyConf;
  }
}
