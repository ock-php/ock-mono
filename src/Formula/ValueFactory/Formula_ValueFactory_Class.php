<?php

namespace Donquixote\Ock\Formula\ValueFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class Formula_ValueFactory_Class implements Formula_ValueFactoryInterface {

  /**
   * Constructor.
   *
   * @param class-string $class
   *   Qualified class name, e.g. 'Acme\Animal\Frog'.
   */
  public function __construct(
    private readonly string $class,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getValueFactory(): CallbackReflectionInterface {
    return new CallbackReflection_ClassConstruction(
      new \ReflectionClass($this->class));
  }

}
