<?php

namespace Donquixote\OCUI\Formula\ValueFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class Formula_ValueFactory_Class implements Formula_ValueFactoryInterface {

  /**
   * The class.
   *
   * @var string
   */
  private $class;

  /**
   * Constructor.
   *
   * @param string $class
   *   Qualified class name, e.g. 'Acme\Animal\Frog'.
   */
  public function __construct(string $class) {
    $this->class = $class;
  }

  /**
   * {@inheritdoc}
   */
  public function getValueFactory(): CallbackReflectionInterface {
    return new CallbackReflection_ClassConstruction(
      new \ReflectionClass($this->class));
  }

}
