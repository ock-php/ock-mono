<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Form\Common\FormatorCommonInterface;

interface FormatorD8Interface extends FormatorCommonInterface {

  /**
   * @param mixed $conf
   * @param string|null $label
   *
   * @return array
   */
  public function confGetD8Form($conf, $label): array;

}
