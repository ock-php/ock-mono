<?php
declare(strict_types=1);

namespace Donquixote\DID\Util;

use Donquixote\DID\Exception\MalformedDeclarationException;

class ReflectionTypeUtil {

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param bool $allowObject
   *   TRUE, to allow 'object' return type.
   *
   * @return class-string|null
   * @psalm-return ($allowObject is true ? (class-string|null) : class-string)
   *   The type, or NULL for 'object'.
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public static function requireGetClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
    bool $allowObject = false,
  ): ?string {
    $type = $reflector instanceof \ReflectionParameter
      ? $reflector->getType()
      : $reflector->getReturnType();
    if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
      if ($allowObject && $type instanceof \ReflectionNamedType && $type->getName() === 'object') {
        return null;
      }
      throw new MalformedDeclarationException(\sprintf(
        'Expected a class-like %s declaration on %s.',
        $reflector instanceof \ReflectionParameter ? 'type' : 'return type',
        MessageUtil::formatReflector($reflector),
      ));
    }
    $name = $type->getName();
    if ($name !== 'self' && $name !== 'static') {
      return $name;
    }
    $reflectionFunction = $reflector instanceof \ReflectionParameter
      ? $reflector->getDeclaringFunction()
      : $reflector;
    if (!$reflectionFunction instanceof \ReflectionMethod) {
      throw new MalformedDeclarationException(\sprintf(
        'Unexpected %s outside class context, in type declaration for %s.',
        "'$name'",
        MessageUtil::formatReflector($reflector),
      ));
    }
    return $reflectionFunction->getDeclaringClass()->getName();
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   *
   * @return class-string|null
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public static function getClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
  ): ?string {
    $type = $reflector instanceof \ReflectionParameter
      ? $reflector->getType()
      : $reflector->getReturnType();
    if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
      return null;
    }
    $name = $type->getName();
    if ($name !== 'self' && $name !== 'static') {
      return $name;
    }
    $reflectionFunction = $reflector instanceof \ReflectionParameter
      ? $reflector->getDeclaringFunction()
      : $reflector;
    if (!$reflectionFunction instanceof \ReflectionMethod) {
      throw new MalformedDeclarationException(\sprintf(
        'Unexpected %s outside class context, in type declaration for %s.',
        "'$name'",
        MessageUtil::formatReflector($reflector),
      ));
    }
    return $reflectionFunction->getDeclaringClass()->getName();
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   *
   * @return string|null
   *   Name of a built-in type, or null.
   */
  public static function getBuiltinType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
  ): ?string {
    $type = $reflector instanceof \ReflectionParameter
      ? $reflector->getType()
      : $reflector->getReturnType();
    if (!$type instanceof \ReflectionNamedType || !$type->isBuiltin()) {
      return null;
    }
    return $type->getName();
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param class-string|null $expected
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public static function requireClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
    ?string $expected,
  ): void {
    $name = self::requireGetClassLikeType($reflector);
    if ($name !== $expected) {
      throw new MalformedDeclarationException(\sprintf(
        'Expected a %s type declaration on %s.',
        $expected,
        MessageUtil::formatReflector($reflector),
      ));
    }
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param string $expected
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public static function requireBuiltinType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
    string $expected,
  ): void {
    $type = $reflector instanceof \ReflectionParameter
      ? $reflector->getType()
      : $reflector->getReturnType();
    if (!$type instanceof \ReflectionNamedType || !$type->isBuiltin() || $type->getName() !== $expected) {
      throw new MalformedDeclarationException(\sprintf(
        'Expected a %s type declaration on %s.',
        $expected,
        MessageUtil::formatReflector($reflector),
      ));
    }
  }

}
