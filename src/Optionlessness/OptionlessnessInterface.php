<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Optionlessness;

use Donquixote\ObCK\Core\Formula\Base\FormulaBaseInterface;
use Donquixote\ObCK\Form\Common\FormatorCommonInterface;

interface OptionlessnessInterface extends FormatorCommonInterface, FormulaBaseInterface {

  /**
   * @return bool
   */
  public function isOptionless(): bool;

}
