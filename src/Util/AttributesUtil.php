<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Util;

use Donquixote\Adaptism\Exception\MalformedDeclarationException;

class AttributesUtil {

  /**
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
   * @template T
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string<T> $name
   *
   * @return T
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   *   None or more than one attribute of the given type.
   */
  public static function requireGetSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): object {
    $reflectionAttribute = self::getOrRequireSingle($reflector, $name, true);
    \assert($reflectionAttribute !== null);
    return $reflectionAttribute->newInstance();
  }

  /**
   * @template T
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector
   * @param class-string<T> $name
   *
   * @return T|null
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  public static function getSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty $reflector,
    string $name,
  ): ?object {
    return self::getOrRequireSingle($reflector, $name, false)
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
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  public static function getOrRequireSingle(
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
