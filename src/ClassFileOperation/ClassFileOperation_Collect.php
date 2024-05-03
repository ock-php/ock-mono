<?php

namespace Donquixote\ClassDiscovery\ClassFileOperation;

class ClassFileOperation_Collect implements ClassFileOperationInterface {

  /**
   * @var string[]
   *   Format: $[$file] = $class
   */
  private array $classFiles = [];

  /**
   * {@inheritdoc}
   */
  public function execute(string $filepath, string $class): void {
    if (isset($this->classFiles[$filepath])) {
      throw new \Exception("File '$filepath' already collected.");
    }
    $this->classFiles[$filepath] = $class;
  }
}
