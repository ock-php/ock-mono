<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8\Optionable;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;
use Donquixote\Cf\Form\D8\FormatorD8Interface;

interface OptionableFormatorD8Interface extends FormatorCommonInterface {

  /**
   * @return \Donquixote\Cf\Form\D8\FormatorD8Interface|null
   */
  public function getOptionalFormator(): ?FormatorD8Interface;

}
