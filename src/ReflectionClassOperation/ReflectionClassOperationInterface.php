<?php

namespace Donquixote\ClassDiscovery\ReflectionClassOperation;

interface ReflectionClassOperationInterface {

  /**
   * Executes the operation for a given class.
   *
   * @param \ReflectionClass $reflClass
   */
  public function execute(\ReflectionClass $reflClass);

}
