<?php
declare(strict_types=1);

namespace Donquixote\Cf\Emptiness;

class Emptiness_Sequence implements EmptinessInterface {

  /**
   * @var \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  private $emptiness;

  /**
   * @param \Donquixote\Cf\Emptiness\EmptinessInterface $emptiness
   */
  public function __construct(EmptinessInterface $emptiness) {
    $this->emptiness = $emptiness;
  }

  /**
   * {@inheritdoc}
   */
  public function confIsEmpty($conf): bool {
    if (NULL === $conf || [] === $conf) {
      return TRUE;
    }
    if (!\is_array($conf)) {
      // Invalid configuration.
      return FALSE;
    }
    foreach ($conf as $delta => $deltaConf) {
      if ($delta[0] === '#') {
        // Invalid delta.
        return FALSE;
      }
      if (!$this->emptiness->confIsEmpty($deltaConf)) {
        // Non-empty configuration for delta.
        return FALSE;
      }
    }
    // All items are empty.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyConf() {
    return [];
  }

}
