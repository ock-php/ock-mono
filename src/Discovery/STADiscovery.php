<?php
declare(strict_types=1);

namespace Donquixote\Ock\Discovery;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\Ock\Discovery\ClassFileToSTAs\ClassFileToSTAs;
use Donquixote\Ock\Discovery\ClassFileToSTAs\ClassFileToSTAsInterface;

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
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
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
