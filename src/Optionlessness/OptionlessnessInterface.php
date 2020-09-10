<?php
declare(strict_types=1);

namespace Donquixote\Cf\Optionlessness;

use Donquixote\Cf\Core\Schema\Base\CfSchemaBaseInterface;
use Donquixote\Cf\Form\Common\FormatorCommonInterface;

interface OptionlessnessInterface extends FormatorCommonInterface, CfSchemaBaseInterface {

  /**
   * @return bool
   */
  public function isOptionless(): bool;

}
