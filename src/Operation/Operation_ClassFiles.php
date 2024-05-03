<?php

namespace Donquixote\ClassDiscovery\Operation;

use Donquixote\ClassDiscovery\ClassFileOperation\ClassFileOperationInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class Operation_ClassFiles implements OperationInterface {

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   * @param \Donquixote\ClassDiscovery\ClassFileOperation\ClassFileOperationInterface $operation
   */
  public function __construct(
    private readonly ClassFilesIAInterface $classFilesIA,
    private readonly ClassFileOperationInterface $operation,
  ) {}

  /**
   * Executes the operation.
   */
  public function execute(): void {
    foreach ($this->classFilesIA as $file => $class) {
      $this->operation->execute($file, $class);
    }
  }
}
