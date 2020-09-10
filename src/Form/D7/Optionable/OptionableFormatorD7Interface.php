<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7\Optionable;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;
use Donquixote\Cf\Form\D7\FormatorD7Interface;

interface OptionableFormatorD7Interface extends FormatorCommonInterface {

  /**
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public function getOptionalFormator(): ?FormatorD7Interface;

}
