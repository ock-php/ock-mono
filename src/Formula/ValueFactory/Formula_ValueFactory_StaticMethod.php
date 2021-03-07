<?php

namespace Donquixote\OCUI\Formula\ValueFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class Formula_ValueFactory_StaticMethod implements Formula_ValueFactoryInterface {

  /**
   * The class name.
   *
   * @var string
   */
  private $class;

  /**
   * The method name.
   *
   * @var string
   */
  private $method;

  /**
   * @param callable $callable
   */
  public static function fromCallable(callable $callable): self {
    if (!is_array($callable) || !is_string($callable[0])) {
      throw new \InvalidArgumentException('Callable must be a static method specified as array.');
    }
    return new self(...$callable);
  }

  /**
   * @param \ReflectionMethod $method
   *
   * @return self
   */
  public static function fromReflectionMethod(\ReflectionMethod $method): self {
    return new self(
      $method->getDeclaringClass()->getName(),
      $method->getName());
  }

  /**
   * Constructor.
   *
   * @param string $class
   *   Qualified class name, e.g. 'Acme\Animal\Frog'.
   * @param string $method
   *   Method name.
   */
  public function __construct(string $class, string $method) {
    $this->class = $class;
    $this->method = $method;
  }

  /**
   * {@inheritdoc}
   */
  public function getValueFactory(): CallbackReflectionInterface {
    return new CallbackReflection_StaticMethod(
      new \ReflectionMethod(
        $this->class,
        $this->method));
  }

}
