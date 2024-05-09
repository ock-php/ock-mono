<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

abstract class ReflectionClassesIABase implements ReflectionClassesIAInterface {

  private ClassFilesReflectionHelper $classFilesReflectionHelper;

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    return ($this->classFilesReflectionHelper ??= new ClassFilesReflectionHelper())->getReflectionClasses($this->getClassfilesIA());
  }

  /**
   * @param \Donquixote\ClassDiscovery\ReflectionClassesIA\ClassFilesReflectionHelper $classFilesReflectionHelper
   *
   * @return static
   */
  public function withClassFilesReflectionHelper(ClassFilesReflectionHelper $classFilesReflectionHelper): static {
    $clone = clone $this;
    $clone->classFilesReflectionHelper = $classFilesReflectionHelper;
    return $clone;
  }

  /**
   * Gets the class files iterator aggregate.
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  abstract protected function getClassfilesIA(): ClassFilesIAInterface;

}
