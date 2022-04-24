<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\ClassFileToSTAs;

interface ClassFileToSTAsInterface {

  /**
   * @param string $class
   * @param string $fileRealpath
   *
   * @return \Donquixote\Ock\IncarnatorPartial\SpecificAdapterInterface[]
   */
  public function classFileGetPartials(string $class, string $fileRealpath): array;

}
