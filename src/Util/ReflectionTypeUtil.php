<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Util;

use Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException;

class ReflectionTypeUtil {

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param bool $allowObject
   *   TRUE, to allow 'object' return type.
   *
   * @return class-string|null
   *   The type, or NULL for 'object'.
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException
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
      throw new MalformedAdapterDeclarationException(\sprintf(
        'Expected a class-like %s declaration on %s.',
        $reflector instanceof \ReflectionParameter ? 'type' : 'return type',
        ReflectionUtil::reflectorDebugName($reflector),
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
      throw new MalformedAdapterDeclarationException(\sprintf(
        'Unexpected %s outside class context, in type declaration for %s.',
        "'$name'",
        ReflectionUtil::reflectorDebugName($reflector),
      ));
    }
    return $reflectionFunction->getDeclaringClass()->getName();
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param class-string|null $expected
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException
   */
  public static function requireClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
    ?string $expected,
  ): void {
    $name = self::requireGetClassLikeType($reflector);
    if ($name !== $expected) {
      throw new MalformedAdapterDeclarationException(\sprintf(
        'Expected a %s type declaration on %s.',
        $expected,
        ReflectionUtil::reflectorDebugName($reflector),
      ));
    }
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param string $expected
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException
   */
  public static function requireBuiltinType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
    string $expected,
  ): void {
    $type = $reflector instanceof \ReflectionParameter
      ? $reflector->getType()
      : $reflector->getReturnType();
    if (!$type instanceof \ReflectionNamedType || !$type->isBuiltin() || $type->getName() !== $expected) {
      throw new MalformedAdapterDeclarationException(\sprintf(
        'Expected a %s type declaration on %s.',
        $expected,
        ReflectionUtil::reflectorDebugName($reflector),
      ));
    }
  }

}
