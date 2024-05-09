<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class ReflectionClassesIA_ClassFilesIA extends ReflectionClassesIABase {

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   */
  public function __construct(
    private readonly ClassFilesIAInterface $classFilesIA,
  ) {}

  /**
   * {@inheritdoc}
   */
  protected function getClassfilesIA(): ClassFilesIAInterface {
    return $this->classFilesIA;
  }

}
