<?php

namespace Donquixote\ClassDiscovery\Operation;

use Donquixote\ClassDiscovery\ClassFileOperation\ClassFileOperationInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class Operation_ClassFiles implements OperationInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private $classFilesIA;

  /**
   * @var \Donquixote\ClassDiscovery\ClassFileOperation\ClassFileOperationInterface
   */
  private $operation;

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   * @param \Donquixote\ClassDiscovery\ClassFileOperation\ClassFileOperationInterface $operation
   */
  public function __construct(ClassFilesIAInterface $classFilesIA, ClassFileOperationInterface $operation) {
    $this->classFilesIA = $classFilesIA;
    $this->operation = $operation;
  }

  /**
   * Executes the operation.
   */
  public function execute() {
    foreach ($this->classFilesIA as $file => $class) {
      $this->operation->execute($file, $class);
    }
  }
}
