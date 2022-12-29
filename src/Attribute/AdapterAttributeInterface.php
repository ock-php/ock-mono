<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Donquixote\Adaptism\Attribute;

/**
 * Marks a class or method as an adapter.
 *
 * If placed on a class, the first parameter of the constructor is considered
 * the adaptee object, and the class instance is considered the adapter.
 *
 * If placed on a method, the first parameter of that method is considered the
 * adaptee, and the return value is considered the adapter.
 *
 * If the method is not static, then an instance will be constructed based on
 * annotated constructor parameters.
 */
interface AdapterAttributeInterface {

  /**
   * @return int|null
   */
  public function getSpecifity(): ?int;

  /**
   * @return bool
   */
  public function isSelfAdapter(): bool;

}
