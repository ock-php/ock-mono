<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Callback;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface Formula_CallbackInterface extends FormulaInterface {

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback(): CallbackReflectionInterface;

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  public function getExplicitParamFormulas(): array;

  /**
   * @return string[]
   */
  public function getExplicitParamLabels(): array;

}
