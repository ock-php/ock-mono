<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;

interface FormatorD8Interface extends FormatorCommonInterface {

  /**
   * @param mixed $conf
   * @param string|null $label
   *
   * @return array
   */
  public function confGetD8Form($conf, $label): array;

}
