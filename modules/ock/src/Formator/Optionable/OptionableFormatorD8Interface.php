<?php
declare(strict_types=1);

namespace Drupal\ock\Formator\Optionable;

use Drupal\ock\Formator\FormatorD8Interface;
use Ock\Ock\Form\Common\FormatorCommonInterface;

interface OptionableFormatorD8Interface extends FormatorCommonInterface {

  /**
   * @return \Drupal\ock\Formator\FormatorD8Interface|null
   */
  public function getOptionalFormator(): ?FormatorD8Interface;

}
