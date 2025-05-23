<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Util;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\Helpers\Util\MessageUtil;
use Ock\Reflection\NameHavingReflectionInterface;
use function Ock\ReflectorAwareAttributes\get_attributes;

/**
 * Helper methods to read attributes from reflectors.
 */
class AttributesUtil {

  /**
   * Finds and instantiates all attributes of a given type.
   *
   * @template T as object
   *
   * @param \ReflectionClass<object>|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string<T> $name
   *
   * @return list<T>
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public static function getAll(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): array {
    return get_attributes($reflector, $name);
  }

  /**
   * @param \ReflectionClass<object>|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string $name
   *
   * @return bool
   *   TRUE, if exactly one attribute of the given type exists.
   *   FALSE, if no attribute of the given type exists.
   *   Exception otherwise.
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  public static function hasSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): bool {
    $attributes = $reflector->getAttributes($name, \ReflectionAttribute::IS_INSTANCEOF);
    if (!$attributes) {
      return false;
    }
    if (count($attributes) > 1) {
      self::failForRepeatedAttribute($name, $reflector);
    }
    return true;
  }

  /**
   * Gets and instantiates the only attribute of a given type.
   *
   * @template T of object
   *
   * @param \ReflectionClass<object>|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string<T> $name
   *
   * @return object
   * @phpstan-return T
   *   Instance from the single attribute.
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   *   None or more than one attribute of the given type.
   */
  public static function requireSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): object {
    $instances = get_attributes($reflector, $name);
    if ($instances === []) {
      self::failForAttributeMissing($name, $reflector);
    }
    if (count($instances) > 1) {
      self::failForRepeatedAttribute($name, $reflector);
    }
    return reset($instances);
  }

  /**
   * Gets and instantiates the first attribute of a given type, if exists.
   *
   * @template T of object
   *
   * @param \ReflectionClass<object>|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   *   Element that has the attribute.
   * @param class-string<T> $name
   *   Expected attribute type/name.
   *
   * @return object|null
   *   Instance from the attribute, or NULL if no matching attribute found.
   *
   * @phpstan-return T|null
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  public static function getSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): ?object {
    $instances = get_attributes($reflector, $name);
    if ($instances === []) {
      return null;
    }
    if (count($instances) > 1) {
      self::failForRepeatedAttribute($name, $reflector);
    }
    return reset($instances);
  }

  /**
   * Throws an exception stating that an attribute is missing.
   *
   * @param class-string $name
   *   Expected attribute class or interface.
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   *   Reflector on which the attribute was expected.
   *
   * @return never
   */
  private static function failForAttributeMissing(
    string $name,
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
  ): never {
    throw new MalformedDeclarationException(sprintf(
      'Required attribute %s missing on %s.',
      $name,
      $reflector instanceof NameHavingReflectionInterface
        ? $reflector->getDebugName()
        : MessageUtil::formatReflector($reflector),
    ));
  }

  /**
   * Throws an exception stating that too many attributes were found.
   *
   * @param class-string $name
   *   Filtering attribute class or interface.
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   *   Reflector on which the attributes were found.
   *
   * @return never
   */
  private static function failForRepeatedAttribute(
    string $name,
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
  ): never {
    throw new MalformedDeclarationException(\sprintf(
      'More than one %s attribute found on %s.',
      $name,
      $reflector instanceof NameHavingReflectionInterface
        ? $reflector->getDebugName()
        : MessageUtil::formatReflector($reflector),
    ));
  }

}
