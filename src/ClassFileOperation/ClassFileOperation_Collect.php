<?php

namespace Donquixote\ClassDiscovery\ClassFileOperation;

class ClassFileOperation_Collect implements ClassFileOperationInterface {

  /**
   * @var string[]
   *   Format: $[$file] = $class
   */
  private $classFiles = [];

  /**
   * Executes an operation for a given class / file.
   *
   * @param string $filepath
   * @param string $class
   *
   * @throws \Exception
   */
  public function execute($filepath, $class) {
    if (isset($this->classFiles[$filepath])) {
      throw new \Exception("File '$filepath' already collected.");
    }
    $this->classFiles[$filepath] = $class;
  }
}
