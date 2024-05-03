<?php

namespace Donquixote\ClassDiscovery\ReflectionClassOperation;

class ReflectionClassOperation_Collect implements ReflectionClassOperationInterface {

  /**
   * @var \ReflectionClass[]
   */
  private array $reflClasses = [];

  /**
   * @return \ReflectionClass[]
   */
  public function getCollected(): array {
    return $this->reflClasses;
  }

  /**
   * Executes the operation for a given class.
   *
   * @param \ReflectionClass $reflClass
   */
  public function execute(\ReflectionClass $reflClass): void {
    $this->reflClasses[] = $reflClass;
  }
}
