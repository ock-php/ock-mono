<?php

declare(strict_types=1);

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

abstract class CurrentNamespaceBase extends ReflectionClassesIABase {

  /**
   * {@inheritdoc}
   */
  protected function getClassfilesIA(): ClassFilesIAInterface {
    try {
      return ClassFilesIA::psr4FromClass(static::class);
    }
    catch (\ReflectionException $e) {
      // Unreachable code.
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }
  }

}
