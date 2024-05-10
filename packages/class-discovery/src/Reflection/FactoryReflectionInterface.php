<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Reflection;

/**
 * @template T of object
 */
interface FactoryReflectionInterface {

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
   * Gets the original class name that this method was requested for.
   *
   * This will be different from ->getDeclaringClass(), if the method is
   * declared in a parent class.
   *
   * @return class-string<T>
   *   Object reflecting the original class.
   *   If this is already a class reflector, it returns the reflector itself.
   */
  public function getClassName(): string;

  /**
   * Gets the original class that this method was requested for.
   *
   * This will be different from ->getDeclaringClass(), if the method is
   * declared in a parent class.
   *
   * @return \Donquixote\ClassDiscovery\Reflection\ClassReflection<T>
   *   Object reflecting the original class.
   *   If this is already a class reflector, it returns the reflector itself.
   */
  public function getClass(): ClassReflection;

  /**
   * Checks if this is a method.
   *
   * @return bool
   *   TRUE if this is a method, FALSE if it is a class.
   */
  public function isMethod(): bool;

  /**
   * Checks if this is a class.
   *
   * @return bool
   *   TRUE if this is a class, FALSE if it is a method.
   */
  public function isClass(): bool;

  /**
   * Checks if a method is declared in a parent class.
   *
   * @return bool
   *   TRUE if the method is declared in a parent class.
   *   If this is a class, it will always return FALSE.
   */
  public function isInherited(): bool;

  /**
   * Gets parameters of the method, or of the class constructor.
   *
   * @return list<\ReflectionParameter>
   *   Parameters of the method, or of the class constructor, or empty array if
   *   a class does not have a constructor.
   */
  public function getParameters(): array;

  /**
   * Checks if the class or method is abstract.
   *
   * @return bool
   *   TRUE if the class or method is abstract, FALSE if not.
   */
  public function isAbstract(): bool;

  /**
   * Checks if method is static or it is a class.
   *
   * A non-static factory must be called with a $this object.
   * A static factory must be called without a $this object.
   * The operation of constructing a new class instance falls clearly into the
   * second category.
   *
   * @return bool
   *   TRUE if this is a class or a static method.
   */
  public function isStatic(): bool;

  /**
   * Checks if the class is instantiable or a method is callable.
   */
  public function isCallable(): bool;

  /**
   * Gets the specified return type.
   *
   * If this is a class, it returns a named type with this class.
   */
  public function getReturnType(): ?\ReflectionType;

  /**
   * Gets the return value class name, if it is unique.
   *
   * @return string|null
   *   If this is a class, the class name is returned.
   *   If this is a method:
   *     - A class name, if the declared return type is a single class name.
   *     - NULL, if the declared type is not a single class name.
   */
  public function getReturnClassName(): string|null;

  /**
   * Gets the return value class, if it is unique.
   *
   * @return \Donquixote\ClassDiscovery\Reflection\ClassReflection|null
   *   The returned class, or NULL if no single return class can be determined.
   *
   * @throws \ReflectionException
   *   Returned class does not exist.
   */
  public function getReturnClass(): ?ClassReflection;

  /**
   * Gets the return value class, if it is unique.
   *
   * @return \Donquixote\ClassDiscovery\Reflection\ClassReflection|null
   *   The returned class, or NULL if no single return class can be determined.
   */
  public function getReturnClassIfExists(): ?ClassReflection;

  /**
   * Gets a name to use in debug and log messages.
   *
   * This name is unique against other classes and class members, assuming these
   * would use different identifier patterns like "$class::$constant" and
   * "$class::\$$property".
   *
   * @return string
   *   For classes, this will be the qualified class name.
   *   For methods, this will be like "$class::$method()".
   */
  public function getDebugName(): string;

  /**
   * Gets a name to use as identifier.
   *
   * This identifier is unique against other classes and methods, but could
   * clash with other class members like properties and constants, assuming
   * these would use the same identifier pattern "$class::$member".
   *
   * @return string
   *   For classes, this will be the qualified class name.
   *   For methods, this will be like "$class::$method".
   */
  public function getFullName(): string;

}
