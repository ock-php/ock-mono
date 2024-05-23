<?php
declare(strict_types=1);

namespace Drupal\ock\Formator\Optionable;

use Donquixote\Ock\Form\Common\FormatorCommonInterface;
use Drupal\ock\Formator\FormatorD8Interface;

interface OptionableFormatorD8Interface extends FormatorCommonInterface {

  /**
   * @return \Drupal\ock\Formator\FormatorD8Interface|null
   */
  public function getOptionalFormator(): ?FormatorD8Interface;

}
