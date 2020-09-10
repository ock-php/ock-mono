<?php
declare(strict_types=1);

namespace Donquixote\Cf\Emptiness;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;

interface EmptinessInterface extends FormatorCommonInterface {

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf): bool;

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   *
   * @todo What if there is no empty configuration?
   */
  public function getEmptyConf();

}
