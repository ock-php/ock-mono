<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Ock\Form\Common\FormatorCommonInterface;

interface FormatorD8Interface extends FormatorCommonInterface {

  /**
   * @param mixed $conf
   * @param string|null $label
   *
   * @return array
   */
  public function confGetD8Form($conf, $label): array;

}
