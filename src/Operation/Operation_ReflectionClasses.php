<?php

namespace Donquixote\ClassDiscovery\Operation;

use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Donquixote\ClassDiscovery\ReflectionClassOperation\ReflectionClassOperationInterface;

class Operation_ReflectionClasses implements OperationInterface {

  /**
   * @param \Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClassesIA
   * @param \Donquixote\ClassDiscovery\ReflectionClassOperation\ReflectionClassOperationInterface $operation
   */
  public function __construct(
    private readonly ReflectionClassesIAInterface $reflectionClassesIA,
    private readonly ReflectionClassOperationInterface $operation,
  ) {}

  /**
   * Executes the operation.
   */
  public function execute(): void {
    foreach ($this->reflectionClassesIA as $reflectionClass) {
      $this->operation->execute($reflectionClass);
    }
  }
}
