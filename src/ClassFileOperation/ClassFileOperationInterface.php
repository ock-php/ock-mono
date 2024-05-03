<?php

namespace Donquixote\ClassDiscovery\ClassFileOperation;

interface ClassFileOperationInterface {

  /**
   * Executes an operation for a given class / file.
   *
   * @param string $filepath
   * @param class-string $class
   *
   * @throws \Exception
   */
  public function execute(string $filepath, string $class);

}
