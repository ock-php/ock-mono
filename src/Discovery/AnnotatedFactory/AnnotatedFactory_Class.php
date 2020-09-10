<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery\AnnotatedFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class AnnotatedFactory_Class implements AnnotatedFactoryInterface {

  /**
   * @var \ReflectionClass
   */
  private $reflectionClass;

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction
   */
  private $callback;

  /**
   * @param \ReflectionClass $reflectionClass
   */
  public function __construct(\ReflectionClass $reflectionClass) {
    $this->reflectionClass = $reflectionClass;
    $this->callback = new CallbackReflection_ClassConstruction($reflectionClass);
  }

  /**
   * @return \ReflectionClass
   */
  public function getReflectionClass(): \ReflectionClass {
    return $this->reflectionClass;
  }

  /**
   * {@inheritdoc}
   */
  public function createDefinition(string $prefix): array {
    return [$prefix . '_class' => $this->reflectionClass->getName()];
  }

  /**
   * {@inheritdoc}
   */
  public function getCallback(): CallbackReflectionInterface {
    return $this->callback;
  }

  /**
   * {@inheritdoc}
   */
  public function getDocComment(): string {
    return $this->reflectionClass->getDocComment();
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnTypeNames(): array {
    return [$this->reflectionClass->getName()];
  }

}
