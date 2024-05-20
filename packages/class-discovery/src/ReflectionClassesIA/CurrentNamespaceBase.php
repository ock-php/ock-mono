<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\ReflectionClassesIA;

use Ock\ClassDiscovery\NamespaceDirectory;

/**
 * Base class to declare a discovery namespace in the current directory.
 */
abstract class CurrentNamespaceBase extends ReflectionClassesIA_ClassFilesIA {

  /**
   * Constructor.
   */
  public function __construct() {
    $classFilesIA = NamespaceDirectory::fromKnownClass(static::class);
    parent::__construct($classFilesIA);
  }

}
