<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Reflection;

interface AttributesHavingReflectionInterface {

  /**
   * @template TT of object
   *
   * Returns an array of attributes.
   *
   * @param class-string<TT>|null $name
   *   Name of an attribute class.
   * @param int $flags
   *   Сriteria by which the attribute is searched.
   * @return list<\ReflectionAttribute<TT>>
   */
  public function getAttributes(?string $name = null, int $flags = 0): array;

  /**
   * Gets attribute instances.
   *
   * This is a shortcut to skip dealing with ReflectionAttribute objects.
   * It also provides better support for static analysis.
   *
   * @template TAttribute of object
   *
   * @param class-string<TAttribute> $name
   *   Attribute class or interface to filter by.
   * @param int $flags
   *   Flags to pass to ->getAttributes().
   *   Pass 0 to only get instances with the exact class.
   *
   * @return list<TAttribute>
   *   Attribute instances.
   */
  public function getAttributeInstances(string $name, int $flags = \ReflectionAttribute::IS_INSTANCEOF): array;

}
