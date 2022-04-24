<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class IncarnatorPartial_Class extends SpecificAdapter_FactoryBase {

  /**
   * @var string
   */
  private $class;

  /**
   * Constructor.
   *
   * @param string $class
   */
  private function __construct(string $class) {
    $this->class = $class;
    parent::__construct(NULL, NULL);
  }

  /**
   * @param \ReflectionClass $reflectionClass
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Exception
   */
  public static function fromClass(\ReflectionClass $reflectionClass, ParamToValueInterface $paramToValue): self {
    $constructor = $reflectionClass->getConstructor();
    if (!$constructor) {
      throw new \Exception('Class must have a constructor.');
    }
    $instance = new self($reflectionClass->getName());
    $instance->initParams(
      $constructor->getParameters(),
      $paramToValue);
    $instance->setResultType($reflectionClass->getName());
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function invokeArgs(array $arguments): ?object {
    return new $this->class(...$arguments);
  }

}
