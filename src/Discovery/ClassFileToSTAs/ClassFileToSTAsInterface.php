<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery\ClassFileToSTAs;

interface ClassFileToSTAsInterface {

  /**
   * @param string $class
   * @param string $fileRealpath
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public function classFileGetPartials(string $class, string $fileRealpath): array;
}
