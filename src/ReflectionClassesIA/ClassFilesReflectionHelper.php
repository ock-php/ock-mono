<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\Reflection\ClassReflection;

/**
 * Helper to convert class files to reflection classes.
 */
class ClassFilesReflectionHelper {

  /**
   * @return \Iterator<int, ClassReflection<object>>
   *   Format: $[$file] = $class
   */
  public function getReflectionClasses(ClassFilesIAInterface $classFilesIA): \Iterator {
    foreach ($classFilesIA->withRealpathRoot() as $file => $class) {
      yield $this->doGetReflectionClass($file, $class);
    }
  }

  /**
   * @param string $file
   * @param string $class
   *
   * @return \Donquixote\ClassDiscovery\Reflection\ClassReflection|null
   */
  public function getReflectionClass(string $file, string $class): ?ClassReflection {
    return $this->doGetReflectionClass(realpath($file), $class);
  }

  /**
   * @param string $realpathFile
   * @param string $class
   *
   * @return \Donquixote\ClassDiscovery\Reflection\ClassReflection|null
   */
  public function doGetReflectionClass(string $realpathFile, string $class): ?ClassReflection {
    try {
      $reflectionClass = new ClassReflection($class);
    }
    catch (\ReflectionException) {
      // Skip non-existing classes / interfaces / traits.
      return null;
    }
    catch (\Error) {
      // Skip if a base class or interface is missing.
      // Unfortunately, missing traits still cause fatal error.
      return null;
    }
    if ($realpathFile !== $reflectionClass->getFileName()) {
      return null;
    }
    return $reflectionClass;
  }

}
