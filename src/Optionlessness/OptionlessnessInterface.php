<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Optionlessness;

use Donquixote\OCUI\Core\Formula\Base\CfSchemaBaseInterface;
use Donquixote\OCUI\Form\Common\FormatorCommonInterface;

interface OptionlessnessInterface extends FormatorCommonInterface, CfSchemaBaseInterface {

  /**
   * @return bool
   */
  public function isOptionless(): bool;

}
