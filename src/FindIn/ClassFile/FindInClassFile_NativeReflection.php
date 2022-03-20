<?php

declare(strict_types=1);

namespace Donquixote\Ock\FindIn\ClassFile;

use Donquixote\Ock\FindIn\ReflectionClass\FindInReflectionClassInterface;

/**
 * @template TKey
 * @template TValue
 *
 * @template-implements FindInClassFileInterface<TKey, TValue>
 */
class FindInClassFile_NativeReflection implements FindInClassFileInterface {

  /**
   * @var \Donquixote\Ock\FindIn\ReflectionClass\FindInReflectionClassInterface
   */
  private $findInReflectionClass;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\FindIn\ReflectionClass\FindInReflectionClassInterface<TKey, TValue> $findInReflectionClass
   */
  public function __construct(FindInReflectionClassInterface $findInReflectionClass) {
    $this->findInReflectionClass = $findInReflectionClass;
  }

  /**
   * {@inheritdoc}
   */
  public function find(string $file, string $class): \Iterator {
    $rc = new \ReflectionClass($class);
    yield from $this->findInReflectionClass->find($rc);
  }

}
