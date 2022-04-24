<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class IncarnatorPartial_Callable extends SpecificAdapter_FactoryBase {

  /**
   * @var callable
   */
  private $callback;

  /**
   * Constructor.
   *
   * @param callable $callback
   *   Typically a static method.
   */
  private function __construct(callable $callback) {
    $this->callback = $callback;
    parent::__construct(NULL, NULL);
  }

  /**
   * @param \ReflectionMethod $reflectionMethod
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Exception
   */
  public static function fromStaticMethod(\ReflectionMethod $reflectionMethod, ParamToValueInterface $paramToValue): self {
    if (!$reflectionMethod->isStatic()) {
      throw new \Exception('Method must be static.');
    }
    $instance = new self([
      $reflectionMethod->getDeclaringClass()->getName(),
      $reflectionMethod->getName(),
    ]);
    $instance->initParams(
      $reflectionMethod->getParameters(),
      $paramToValue);
    $rtype = $reflectionMethod->getReturnType();
    if ($rtype instanceof \ReflectionNamedType && !$rtype->isBuiltin()) {
      /** @var class-string|'self'|'static' $class */
      $class = $rtype->getName();
      if ($class === 'self') {
        $class = $reflectionMethod->getDeclaringClass()->getName();
      }
      $instance->setResultType($class);
    }
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function invokeArgs(array $arguments): ?object {
    return ($this->callback)(...$arguments);
  }

}
