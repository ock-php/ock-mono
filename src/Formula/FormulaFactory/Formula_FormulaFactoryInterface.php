<?php

namespace Donquixote\Ock\Formula\FormulaFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;

interface Formula_FormulaFactoryInterface extends FormulaInterface {

  /**
   * Gets the factory.
   *
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   *   The factory.
   *
   * @throws \Exception
   *   Factory method cannot be created.
   */
  public function getFormulaFactory(): CallbackReflectionInterface;

}
