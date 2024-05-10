<?php

declare(strict_types=1);

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

use Donquixote\ClassDiscovery\NamespaceDirectory;

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
