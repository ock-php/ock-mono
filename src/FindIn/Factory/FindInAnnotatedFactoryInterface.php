<?php

declare(strict_types=1);

namespace Donquixote\Ock\FindIn\Factory;

use Donquixote\Ock\MetadataList\MetadataListInterface;

/**
 * @template TKey
 * @template TValue
 */
interface FindInAnnotatedFactoryInterface {

  /**
   * @param \ReflectionClass $reflectionClass
   * @param \Donquixote\Ock\MetadataList\MetadataListInterface $metadata
   *
   * @return \Iterator|iterable<TKey, TValue>
   *
   * @throws \Exception
   *   Class does not exist, or something else is wrong.
   */
  public function findInAnnotatedClass(\ReflectionClass $reflectionClass, MetadataListInterface $metadata): \Iterator;

  /**
   * @param \ReflectionMethod $reflectionMethod
   * @param \Donquixote\Ock\MetadataList\MetadataListInterface $metadata
   *
   * @return \Iterator|iterable<TKey, TValue>
   *
   * @throws \Exception
   *   Method does not exist, or behaves wrong.
   */
  public function findInAnnotatedMethod(\ReflectionMethod $reflectionMethod, MetadataListInterface $metadata): \Iterator;

}
