<?php

declare(strict_types=1);

namespace Donquixote\Ock\FindIn\ClassFile;

/**
 * @template TKey
 * @template TValue
 */
interface FindInClassFileInterface {

  /**
   * @param string $file
   * @param class-string $class
   *
   * @return \Iterator<TKey, TValue>
   *
   * @throws \Exception
   *   Class does not exist, or something else is wrong.
   */
  public function find(string $file, string $class): \Iterator;

}
