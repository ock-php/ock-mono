<?php

namespace Donquixote\OCUI\Formula\ValueFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface Formula_ValueFactoryInterface extends FormulaInterface {

  /**
   * Gets the factory.
   *
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   *   The factory to create a value.
   *
   * @throws \Exception
   *   Factory method cannot be created.
   */
  public function getValueFactory(): CallbackReflectionInterface;



}
