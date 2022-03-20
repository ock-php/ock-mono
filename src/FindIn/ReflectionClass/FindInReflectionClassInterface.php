<?php

declare(strict_types=1);

namespace Donquixote\Ock\FindIn\ReflectionClass;

/**
 * @template TKey
 * @template TValue
 */
interface FindInReflectionClassInterface {

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Iterator|iterable<TKey, TValue>
   *
   * @throws \Exception
   *   Class does not exist, or something else is wrong.
   */
  public function find(\ReflectionClass $reflectionClass): \Iterator;

}
