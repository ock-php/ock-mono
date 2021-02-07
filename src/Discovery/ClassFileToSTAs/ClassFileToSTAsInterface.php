<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Discovery\ClassFileToSTAs;

interface ClassFileToSTAsInterface {

  /**
   * @param string $class
   * @param string $fileRealpath
   *
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   */
  public function classFileGetPartials(string $class, string $fileRealpath): array;
}
