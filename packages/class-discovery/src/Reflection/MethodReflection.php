<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Reflection;

/**
 * Reflection method which remembers the original class it was requested for.
 *
 * @template T of object
 *
 * @template-implements \Ock\ClassDiscovery\Reflection\FactoryReflectionInterface<T>
 */
class MethodReflection extends \ReflectionMethod implements FactoryReflectionInterface {

  /**
   * Original class name that was passed in the constructor.
   *
   * @var class-string<T>
   */
  public readonly string $originalClass;

  /**
   * Constructor.
   *
   * @param object|string $objectOrClass
   *   Object or class name.
   *   Unlike the parent class, this does not accept "$class::$method" strings.
   * @param string $method
   *   The method name.
   *   Unlike the parent class, this does not accept NULL.
   *
   * @phpstan-param T|class-string<T> $objectOrClass
   * @psalm-param T|class-string<T> $objectOrClass
   *
   * @throws \ReflectionException
   */
  public function __construct(
    object|string $objectOrClass,
    string $method,
  ) {
    if (is_object($objectOrClass)) {
      $this->originalClass = get_class($objectOrClass);
    }
    else {
      $this->originalClass = $objectOrClass;
    }
    parent::__construct($objectOrClass, $method);
  }

  /**
   * {@inheritdoc}
   */
  public function getClassName(): string {
    return $this->originalClass;
  }

  /**
   * {@inheritdoc}
   */
  public function getClass(): ClassReflection {
    try {
      return new ClassReflection($this->originalClass);
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException('Unreachable code.', 0, $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function isMethod(): true {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function isClass(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   * @return bool
   */
  public function isInherited(): bool {
    return $this->originalClass !== $this->class;
  }

  /**
   * {@inheritdoc}
   */
  public function isCallable(): bool {
    return !$this->isAbstract() && !$this->isConstructor() && $this->isPublic();
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnClassName(): string|null {
    $type = $this->getReturnType();
    if (!$type instanceof \ReflectionNamedType
      || $type->isBuiltin()
    ) {
      return null;
    }
    $name = $type->getName();
    return match ($name) {
      'self' => $this->class,
      'static' => $this->originalClass,
      default => $name,
    };
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnClass(): ?ClassReflection {
    $class = $this->getReturnClassName();
    if ($class === null) {
      return null;
    }
    return new ClassReflection($class);
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnClassIfExists(): ?ClassReflection {
    try {
      return $this->getReturnClass();
    }
    catch (\ReflectionException) {
      return null;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDebugName(): string {
    return $this->originalClass . '::' . $this->name . '()';
  }

  /**
   * {@inheritdoc}
   */
  public function getFullName(): string {
    return $this->originalClass . '::' . $this->name;
  }

  /**
   * Gets a callable array to call this as a static function.
   *
   * @return callable&array{class-string, string}
   */
  public function getStaticCallableArray(): array {
    return [$this->originalClass, $this->name];
  }

}
