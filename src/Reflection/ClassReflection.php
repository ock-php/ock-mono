<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Reflection;

/**
 * @template T of object
 *
 * @template-extends \ReflectionClass<T>
 * @template-implements \Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface<T>
 */
class ClassReflection extends \ReflectionClass implements FactoryReflectionInterface {

  /**
   * Constructor.
   *
   * @phpstan-param T|class-string<T> $objectOrClass
   * @psalm-param T|class-string<T> $objectOrClass
   *
   * @throws \ReflectionException
   *   Class does not exist or has missing dependencies.
   */
  public function __construct(object|string $objectOrClass) {
    try {
      parent::__construct($objectOrClass);
    }
    catch (\Error $e) {
      assert(is_string($objectOrClass));
      throw new \ReflectionException(sprintf(
        'Class % is not available: %s',
        $objectOrClass,
        $e->getMessage(),
      ), 0, $e);
    }
  }

  /**
   * Creates an instance from a class name that is known to exist.
   *
   * @param class-string<T> $name
   *
   * @return self<T>
   */
  public static function createKnown(string $name): self {
    try {
      return new static($name);
    }
    catch (\ReflectionException|\Error $e) {
      // The class does not exist, or one of its parents or interfaces is
      // missing.
      throw new \RuntimeException("Known class $name was not found or could not be loaded.", 0, $e);
    }
  }

  /**
   * Static factory. Creates an instance if the class exists.
   *
   * @param class-string<T> $name
   *   Class name.
   *
   * @return self<T>|null
   *   New instance, or NULL if the class does not exist, or if one of its
   *   parents or interfaces is missing.
   */
  public static function createIfExists(string $name): self|null {
    try {
      return new static($name);
    }
    catch (\ReflectionException|\Error) {
      // The class does not exist, or one of its parents or interfaces is
      // missing.
      return null;
    }
  }

  /**
   * @param int|null $filter
   *
   * @return list<\Donquixote\ClassDiscovery\Reflection\MethodReflection<T>>
   */
  public function getMethods(int|null $filter = null): array {
    try {
      return array_map(
        fn (\ReflectionMethod $method) => new MethodReflection($this->name, $method->getName()),
        parent::getMethods($filter),
      );
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException('Unreachable code.', 0, $e);
    }
  }

  /**
   * Gets methods that match the filters.
   *
   * @param int|null $filter
   *
   * @return list<\Donquixote\ClassDiscovery\Reflection\MethodReflection<T>>
   */
  public function getCallableMethods(int|null $filter = null): array {
    $methods = parent::getMethods($filter);
    $result = [];
    foreach ($methods as $method) {
      if ($method->isAbstract() || $method->isConstructor() || !$method->isPublic()) {
        continue;
      }
      try {
        $result[] = new MethodReflection($this->name, $method->getName());
      }
      catch (\ReflectionException $e) {
        throw new \RuntimeException('Unreachable code.', 0, $e);
      }
    }
    return $result;
  }

  /**
   * Gets methods that match the filters.
   *
   * @param int|null $filter
   * @param bool|null $static
   * @param bool|null $public
   * @param bool|null $protected
   * @param bool|null $private
   * @param bool|null $abstract
   * @param bool|null $final
   *
   * @return list<\Donquixote\ClassDiscovery\Reflection\MethodReflection<T>>
   */
  public function getFilteredMethods(
    int $filter = null,
    bool $static = null,
    bool $public = null,
    bool $protected = null,
    bool $private = null,
    bool $abstract = null,
    bool $final = null,
  ): array {
    $methods = parent::getMethods($filter);
    $result = [];
    foreach ($methods as $method) {
      if ($static !== null && $static !== $method->isStatic()) {
        continue;
      }
      if ($public !== null && $public !== $method->isPublic()) {
        continue;
      }
      if ($protected !== null && $protected !== $method->isProtected()) {
        continue;
      }
      if ($private !== null && $private !== $method->isPrivate()) {
        continue;
      }
      if ($abstract !== null && $abstract !== $method->isAbstract()) {
        continue;
      }
      if ($final !== null && $final !== $method->isFinal()) {
        continue;
      }
      try {
        $result[] = new MethodReflection($this->name, $method->getName());
      }
      catch (\ReflectionException $e) {
        throw new \RuntimeException('Unreachable code.', 0, $e);
      }
    }
    return $result;
  }

  /**
   * Gets the method with the given name.
   *
   * @param string $name
   *   Method name.
   *
   * @return \Donquixote\ClassDiscovery\Reflection\MethodReflection<T>
   *   The method.
   *
   * @throws \ReflectionException
   *   Method does not exist.
   */
  public function getMethod(string $name): MethodReflection {
    return new MethodReflection($this->name, $name);
  }

  /**
   * @return \Donquixote\ClassDiscovery\Reflection\MethodReflection<T>|null
   */
  public function getConstructor(): ?MethodReflection {
    if (!$this->hasMethod('__construct')) {
      return null;
    }
    try {
      return new MethodReflection($this->name, '__construct');
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException('Unreachable code.', 0, $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getClassName(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getClass(): static {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isMethod(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function isClass(): true {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function isInherited(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function getParameters(): array {
    return parent::getConstructor()?->getParameters() ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function isStatic(): true {
    // Constructing a class does not need a $this object, so this factory counts
    // as static.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function isCallable(): bool {
    return $this->isInstantiable();
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnType(): ReflectionClassType {
    return new ReflectionClassType($this->name);
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnClassName(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnClass(): static {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnClassIfExists(): static {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDebugName(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getFullName(): string {
    return $this->name;
  }

  /**
   * Gets interface names including the interface itself if it is one.
   *
   * @return list<class-string>
   */
  public function getInclusiveInterfaceNames(): array {
    $names = $this->getInterfaceNames();
    if ($this->isInterface()) {
      $names = [$this->name, ...$names];
    }
    return $names;
  }

}
