<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Util;

use Donquixote\Adaptism\Exception\MalformedDeclarationException;

/**
 * Helper methods to read attributes from reflectors.
 */
class AttributesUtil {

  /**
   * Finds and instantiates all attributes of a given type.
   *
   * @template T as object
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string<T> $name
   *
   * @return list<T>
   */
  public static function getInstances(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): array {
    $instances = [];
    /** @var \ReflectionAttribute<T> $attribute */
    foreach ($reflector->getAttributes($name, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
      $instances[] = $attribute->newInstance();
    }
    return $instances;
  }

  /**
   * Asserts that axactly one
   *
   * @template T
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string<T> $name
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   *   None or more than one attribute of the given type.
   */
  public static function requireHasSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): void {
    self::getOrRequireSingle($reflector, $name, true);
  }

  /**
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string $name
   *
   * @return bool
   *   TRUE, if exactly one attribute of the given type exists.
   *   FALSE, if no attribute of the given type exists.
   *   Exception otherwise.
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  public static function hasSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): bool {
    return self::getOrRequireSingle($reflector, $name, false) !== null;
  }

  /**
   * Gets and instantiates the only attribute of a given type.
   *
   * @template T
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string<T> $name
   *
   * @return T
   *   Instance from the single attribute.
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   *   None or more than one attribute of the given type.
   */
  public static function requireGetSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): object {
    /**
     * @var \ReflectionAttribute<T> $reflectionAttribute
     * @psalm-ignore-var
     */
    $reflectionAttribute = self::getOrRequireSingle($reflector, $name, true);
    return $reflectionAttribute->newInstance();
  }

  /**
   * Gets and instantiates the first attribute of a given type, if exists.
   *
   * @template T
   * @template force as bool
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   *   Element that has the attribute.
   * @param class-string<T> $name
   *   Expected attribute type/name.
   * @param bool $force
   * @psalm-param force $force
   *
   * @return T|null
   *   Instance from the attribute, or NULL if no matching attribute found.
   * @psalm-return (force is true ? T : (T|null))
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  public static function getSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
    bool $force = FALSE,
  ): ?object {
    return self::getOrRequireSingle($reflector, $name, $force)
      ?->newInstance();
  }

  /**
   * @template T as object
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string<T> $name
   * @param bool $require
   *
   * @return \ReflectionAttribute<T>|null
   * @psalm-return ($require is true ? \ReflectionAttribute<T> : \ReflectionAttribute<T>|null)
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  private static function getOrRequireSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
    bool $require,
  ): ?\ReflectionAttribute {
    /** @var \ReflectionAttribute<T>[] $attributes */
    $attributes = $reflector->getAttributes($name, \ReflectionAttribute::IS_INSTANCEOF);
    if (!$attributes && !$require) {
      return null;
    }
    if (\array_keys($attributes) !== [0]) {
      throw new MalformedDeclarationException(\sprintf(
        'Expected %s one #[%s] attribute on %s, found %s',
        $require ? 'exactly' : 'up to',
        $name,
        MessageUtil::formatReflector($reflector),
        count($attributes),
      ));
    }
    return $attributes[0];
  }

}
