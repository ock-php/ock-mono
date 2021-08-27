<?php
declare(strict_types=1);

namespace Drupal\cu\Formator\Optionable;

use Donquixote\ObCK\Form\Common\FormatorCommonInterface;
use Drupal\cu\Formator\FormatorD8Interface;

interface OptionableFormatorD8Interface extends FormatorCommonInterface {

  /**
   * @return \Drupal\cu\Formator\FormatorD8Interface|null
   */
  public function getOptionalFormator(): ?FormatorD8Interface;

}
