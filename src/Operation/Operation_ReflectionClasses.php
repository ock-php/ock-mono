<?php

namespace Donquixote\ClassDiscovery\Operation;

use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Donquixote\ClassDiscovery\ReflectionClassOperation\ReflectionClassOperationInterface;

class Operation_ReflectionClasses implements OperationInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface
   */
  private $reflectionClassesIA;

  /**
   * @var \Donquixote\ClassDiscovery\ReflectionClassOperation\ReflectionClassOperationInterface
   */
  private $operation;

  /**
   * @param \Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClassesIA
   * @param \Donquixote\ClassDiscovery\ReflectionClassOperation\ReflectionClassOperationInterface $operation
   */
  public function __construct(ReflectionClassesIAInterface $reflectionClassesIA, ReflectionClassOperationInterface $operation) {
    $this->reflectionClassesIA = $reflectionClassesIA;
    $this->operation = $operation;
  }

  /**
   * Executes the operation.
   */
  public function execute() {
    foreach ($this->reflectionClassesIA as $reflectionClass) {
      $this->operation->execute($reflectionClass);
    }
  }
}
