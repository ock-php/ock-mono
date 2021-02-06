<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Discovery;

use Donquixote\OCUI\Discovery\ClassFileToSTAs\ClassFileToSTAs;
use Donquixote\OCUI\Discovery\ClassFileToSTAs\ClassFileToSTAsInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class STADiscovery {

  /**
   * @var ClassFileToSTAsInterface
   */
  private $classFileToAdapters;

  /**
   * @return self
   */
  public static function create(): STADiscovery {
    return new self(ClassFileToSTAs::create());
  }

  /**
   * @param ClassFileToSTAsInterface $classFileToAdapters
   */
  public function __construct(ClassFileToSTAsInterface $classFileToAdapters) {
    $this->classFileToAdapters = $classFileToAdapters;
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public function classFilesIAGetPartials(ClassFilesIAInterface $classFilesIA): array {

    $partials = [];
    foreach ($classFilesIA->withRealpathRoot() as $fileRealpath => $class) {
      foreach ($this->classFileToAdapters->classFileGetPartials($class, $fileRealpath) as $partial) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }
}
