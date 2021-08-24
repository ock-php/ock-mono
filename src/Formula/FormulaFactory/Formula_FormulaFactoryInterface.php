<?php

namespace Donquixote\ObCK\Formula\FormulaFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;

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
