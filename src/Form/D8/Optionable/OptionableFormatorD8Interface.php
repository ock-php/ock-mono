<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8\Optionable;

use Donquixote\OCUI\Form\Common\FormatorCommonInterface;
use Donquixote\OCUI\Form\D8\FormatorD8Interface;

interface OptionableFormatorD8Interface extends FormatorCommonInterface {

  /**
   * @return \Donquixote\OCUI\Form\D8\FormatorD8Interface|null
   */
  public function getOptionalFormator(): ?FormatorD8Interface;

}
