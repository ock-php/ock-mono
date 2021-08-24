<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Discovery\ClassFileToSTAs;

interface ClassFileToSTAsInterface {

  /**
   * @param string $class
   * @param string $fileRealpath
   *
   * @return \Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  public function classFileGetPartials(string $class, string $fileRealpath): array;
}
