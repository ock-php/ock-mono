<?php
declare(strict_types=1);

namespace Ock\ClassDiscovery\Util;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\ClassDiscovery\Reflection\NameHavingReflectionInterface;
use Ock\Helpers\Util\MessageUtil;

class ReflectionTypeUtil {

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass<object>|FactoryReflectionInterface<object> $reflector
   * @param bool $allowObject
   *   TRUE, to allow 'object' return type.
   *
   * @return class-string|null
   * @phpstan-return ($allowObject is true ? (class-string|null) : class-string)
   *   The type, or NULL for 'object'.
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   *   The return type does not match the expectation.
   */
  public static function requireGetClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass|FactoryReflectionInterface $reflector,
    bool $allowObject = false,
  ): ?string {
    $type = self::readType($reflector);
    if ($type !== false) {
      if ($type !== true) {
        return $type;
      }
      if ($allowObject) {
        return null;
      }
    }
    throw new MalformedDeclarationException(\sprintf(
      'Expected a class-like %s declaration on %s.',
      $reflector instanceof \ReflectionParameter ? 'type' : 'return type',
      $reflector instanceof NameHavingReflectionInterface
        ? $reflector->getDebugName()
        : MessageUtil::formatReflector($reflector),
    ));
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass<object>|FactoryReflectionInterface<object> $reflector
   *
   * @return class-string|null
   */
  public static function getClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass|FactoryReflectionInterface $reflector,
  ): ?string {
    $type = self::readType($reflector);
    return match ($type) {
      true, false => null,
      default => $type,
    };
  }

  /**
   * Gets the return value class name, if it is unique.
   *
   * @param \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass<object>|\Ock\ClassDiscovery\Reflection\FactoryReflectionInterface<object> $reflector
   *   Reflector to analyse.
   *
   * @return class-string|bool
   *   If this is a class, the class name is returned.
   *   If this is a method or function:
   *     - A class name, if the declared return type is a single class name.
   *     - TRUE, if the single declared return type is 'object'.
   *     - FALSE, if the declared type is not a single class name.
   *   If this is a parameter:
   *     - A class name, if the declared parameter type is a single class name.
   *     - TRUE, if the single declared parameter type is 'object'.
   *     - FALSE, if the declared parameter type is not a single class name.
   */
  protected static function readType(
    \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass|FactoryReflectionInterface $reflector,
  ): string|bool {
    if ($reflector instanceof \ReflectionClass) {
      return $reflector->getName();
    }
    if ($reflector instanceof \ReflectionParameter) {
      $type = $reflector->getType();
    }
    else {
      $type = $reflector->getReturnType();
    }
    if (!$type instanceof \ReflectionNamedType) {
      return false;
    }
    $name = $type->getName();
    if ($type->isBuiltin()) {
      if ($name === 'object') {
        return true;
      }
      return false;
    }
    /** @var class-string|'self'|'static' $name */
    if ($name === 'self' || $name === 'static') {
      $reflectionFunction = $reflector instanceof \ReflectionParameter
        ? $reflector->getDeclaringFunction()
        : $reflector;
      if (!$reflectionFunction instanceof \ReflectionMethod) {
        throw new \RuntimeException(\sprintf(
          'Unexpected %s outside class context, in type declaration for %s.',
          "'$name'",
          $reflector instanceof NameHavingReflectionInterface
            ? $reflector->getDebugName()
            : MessageUtil::formatReflector($reflector),
        ));
      }
      $name = $reflectionFunction->getDeclaringClass()->getName();
    }
    return $name;
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
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
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
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
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
