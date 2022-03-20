<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\Ock\FindIn\ClassFile\FindInClassFileInterface;

class Discovery_ClassFilesIA implements DiscoveryInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private $classFilesIA;

  /**
   * @var \Donquixote\Ock\FindIn\ClassFile\FindInClassFileInterface
   */
  private $findInClassFile;

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   * @param \Donquixote\Ock\FindIn\ClassFile\FindInClassFileInterface $findInClassFile
   */
  public function __construct(ClassFilesIAInterface $classFilesIA, FindInClassFileInterface $findInClassFile) {
    $this->classFilesIA = $classFilesIA;
    $this->findInClassFile = $findInClassFile;
  }

  /**
   * {@inheritdoc}
   */
  public function discover(?array &$exceptions): \Iterator {
    $exceptions = [];
    foreach ($this->classFilesIA->withRealpathRoot() as $file => $class) {
      try {
        yield from $this->findInClassFile->find($file, $class);
      }
      catch (\Exception $e) {
        $exceptions[$class][] = $e;
      }
    }
  }

}
