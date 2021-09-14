<?php

declare(strict_types=1);

namespace Donquixote\Ock\Util;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

final class ReflectionUtil extends UtilBase {

  private const FQCN_PATTERN = '@^(\\\\[a-zA-Z_][a-zA-Z_0-9]*)+$@';

  private const PRIMITIVE_TYPES = ['boolean', 'bool', 'integer', 'double', 'float', 'string', 'array', 'object', 'resource', 'null', 'false', 'true', 'callable'];

  /**
   * @param callable $callable
   *
   * @return string[]
   *
   * @throws \ReflectionException
   *   Function or method does not exist.
   * @throws \Exception
   *   Malformed callable definition.
   */
  public static function callableGetReturnTypeNames(callable $callable): array {
    $reflFunction = self::callableGetReflectionFunction($callable);
    return self::functionGetReturnTypeNames($reflFunction);
  }

  /**
   * Gets a function-like reflector for a callable.
   *
   * @param mixed $callable
   *   Callable.
   *
   * @return \ReflectionFunctionAbstract
   *   Function-like reflector.
   *
   * @throws \ReflectionException
   *   Function or method does not exist.
   * @throws \Exception
   *   Malformed callable definition.
   */
  public static function callableGetReflectionFunction($callable): \ReflectionFunctionAbstract {

    if ($callable instanceof \Closure) {
      return new \ReflectionFunction($callable);
    }

    if (\is_string($callable)) {
      return FALSE === strpos($callable, '::')
        ? new \ReflectionFunction($callable)
        : new \ReflectionMethod($callable);
    }

    if (\is_array($callable)) {
      if (!isset($callable[0], $callable[1])) {
        throw new \Exception('Malformed callback array.');
      }
      return new \ReflectionMethod($callable[0], $callable[1]);
    }

    if (\is_object($callable)) {
      return new \ReflectionMethod($callable, '__invoke');
    }

    throw new \Exception('Malformed callback definition.');
  }

  /**
   * @param \Closure $closure
   *
   * @return string[]
   */
  public static function closureGetReturnClassNames(\Closure $closure): array {
    try {
      $reflFunction = new \ReflectionFunction($closure);
    }
    catch (\ReflectionException $e) {
      // A closure is always a valid function. This is impossible.
      throw new \RuntimeException('Impossible exception.', 0, $e);
    }

    return self::functionGetReturnTypeNames($reflFunction);
  }

  /**
   * @param \ReflectionFunctionAbstract $function
   *
   * @return string[]
   */
  public static function functionGetReturnTypeNames(\ReflectionFunctionAbstract $function): array {
    if ($function instanceof \ReflectionMethod) {
      $declaringClass = $function->getDeclaringClass();
      $declaringClassName = $declaringClass->getName();
      $aliasMap = [];
      $namespace = $declaringClass->getNamespaceName();
    }
    else {
      $declaringClassName = NULL;
      $aliasMap = [];
      $namespace = $function->getNamespaceName();
    }

    if (NULL !== $returnType = $function->getReturnType()) {
      try {
        /** @var string[] $names */
        $names = [];
        self::reflectionTypeReadClassNames($names, $returnType, $declaringClassName);
        return $names;
      }
      catch (\Exception $e) {
        // Unsupported subclass of \ReflectionType.
        // Fall through to doc-based detection.
      }
    }

    $docComment = $function->getDocComment();

    if ($docComment === NULL) {
      return [];
    }

    return self::docGetReturnTypeClassNames(
      $docComment,
      $declaringClassName,
      $aliasMap,
      $namespace);
  }

  /**
   * @param string[] $names
   * @param \ReflectionType $type
   * @param string|null $declaringClassName
   *
   * @throws \Exception
   *   Unsupported subclass of ReflectionType.
   */
  public static function reflectionTypeReadClassNames(array &$names, \ReflectionType $type, ?string $declaringClassName): void {

    if ($type instanceof \ReflectionNamedType) {
      if (!$type->isBuiltin()) {
        $name = $type->getName();
        if ($name === 'static' || $name === 'self') {
          $name = $declaringClassName;
        }
        $names[] = $name;
      }
    }
    /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */
    elseif ($type instanceof \ReflectionUnionType) {
      foreach ($type->getTypes() as $part) {
        self::reflectionTypeReadClassNames($names, $part, $declaringClassName);
      }
    }
    else {
      $class = get_class($type);
      throw new \Exception("Unsupported subclass '$class' of \\ReflectionType.");
    }
  }

  /**
   * @param string $docComment
   * @param string|null $selfClassName
   * @param string[] $aliasMap
   *   Format: $[$alias] = $class
   * @param string|null $namespace
   *
   * @return string[]
   */
  public static function docGetReturnTypeClassNames(
    string $docComment,
    string $selfClassName = NULL,
    array $aliasMap = [],
    string $namespace = NULL
  ): array {

    if (NULL !== $selfClassName) {
      $aliasMap['$this'] = $aliasMap['self'] = $aliasMap['static'] = $selfClassName;
    }

    $names = [];
    foreach (self::docGetReturnTypeAliases($docComment) as $alias) {

      if (NULL !== $name = self::aliasGetClassName($alias, $aliasMap, $namespace)) {
        $names[] = $name;
      }
    }

    return $names;
  }

  /**
   * @param string $docComment
   *
   * @return string[]
   */
  public static function docGetReturnTypeAliases(string $docComment): array {

    if (!preg_match('~(?:^/\*\*\ +|\v\h*\* )@return\h+(\S+)~', $docComment, $m)) {
      return [];
    }

    $aliases = [];
    foreach (explode('|', $m[1]) as $alias) {

      if ('' === $alias) {
        continue;
      }

      $aliases[] = $alias;
    }

    return $aliases;
  }

  /**
   * @param string $alias
   * @param string[] $aliasMap
   * @param string|null $namespace
   *
   * @return null|string
   */
  public static function aliasGetClassName(string $alias, array $aliasMap = [], $namespace = NULL): ?string {

    if ('' === $alias) {
      return NULL;
    }

    if ('\\' === $alias[0]) {
      // This seems to be an FQCN.

      if (!preg_match(self::FQCN_PATTERN, $alias)) {
        // But it is not.
        return NULL;
      }

      // Oh yes it is!
      return substr($alias, 1);
    }

    if (isset($aliasMap[$alias])) {
      return $aliasMap[$alias];
    }

    if (\in_array(strtolower($alias), self::PRIMITIVE_TYPES, TRUE)) {
      // Ignore primitive types.
      return NULL;
    }

    if (NULL === $namespace) {
      // Namespace is not known, we cannot do further magic.
      return NULL;
    }

    if (!preg_match(self::FQCN_PATTERN, '\\' . $alias)) {
      return NULL;
    }

    if ('' === $namespace) {
      return $alias;
    }

    return $namespace . '\\' . $alias;
  }

  /**
   * @param object $object
   * @param string $k
   * @param string|null $context
   *
   * @return mixed
   */
  public static function &objectGetPropertyValueRef(object $object, string $k, $context = NULL) {

    if (NULL === $context) {
      /** @var string|object|null $context */
      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      $context = $object;
    }

    // See https://stackoverflow.com/a/17560595/246724
    $closure = static function & ($k) use ($object) {
      // Using $object instead of $this, to prevent IDE warnings.
      return $object->$k;
    };

    $bound = $closure->bindTo(NULL, $context);

    /** @noinspection PhpPassByRefInspection */
    return $bound->__invoke($k);
  }

  /**
   * @param object $object
   * @param string[] $trail
   *
   * @return mixed|null
   */
  public static function objectGetPropertyValueAtTrail(object $object, array $trail) {

    foreach ($trail as $k) {
      if (\is_object($object)) {
        $object = self::objectGetPropertyValue($object, $k);
      }
      elseif (\is_array($object)) {
        $object = $object[$k];
      }
      else {
        return NULL;
      }
    }

    return $object;
  }

  /**
   * @param object $object
   * @param string $k
   * @param string|null $context
   *
   * @return mixed
   */
  public static function objectGetPropertyValue(object $object, string $k, $context = NULL) {

    if (NULL === $context) {
      /** @var string|object|null $context */
      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      $context = $object;
    }

    // See https://stackoverflow.com/a/17560595/246724
    $closure = static function ($k) use ($object) {
      // Using $object instead of $this, to prevent IDE warnings.
      return $object->$k;
    };

    $bound = $closure->bindTo(NULL, $context);

    return $bound->__invoke($k);
  }

  /**
   * @param object $object
   *
   * @return mixed[][]
   *   Format: $[$level][$name] = $value
   */
  public static function objectGetPropertyValuesDeep(object $object): array {

    // See https://stackoverflow.com/a/17560595/246724
    $closure = static function & ($k) use ($object) {
      // Using $object instead of $this, to prevent IDE warnings.
      return $object->$k;
    };

    $bound = $closure->bindTo(NULL, $object);

    $reflObject = new \ReflectionObject($object);

    $valuess = [];
    foreach ($reflObject->getProperties() as $property) {

      if ($property->isStatic()) {
        continue;
      }

      $k = $property->getName();
      /** @noinspection PhpPassByRefInspection */
      $valuess[0][$k] = &$bound->__invoke($k);
    }

    $reflClass = $reflObject;

    $level = 0;
    while ($reflClass = $reflClass->getParentClass()) {
      ++$level;
      $bound = $closure->bindTo(NULL, $reflClass->getName());
      foreach ($reflClass->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {

        if ($property->isStatic()) {
          continue;
        }

        $k = $property->getName();

        /** @noinspection PhpPassByRefInspection */
        $valuess[$level][$k] = &$bound->__invoke($k);
      }
    }

    return $valuess;
  }

  /**
   * @param string $class
   * @param mixed[] $values
   * @param mixed[][] $privateParentValues
   *   Format: $[$level][$name] = $value
   *
   * @return object
   *
   * @throws \ReflectionException
   */
  public static function createInstance(string $class, array $values, array $privateParentValues = []): object {

    $reflClass = new \ReflectionClass($class);
    $object = $reflClass->newInstanceWithoutConstructor();

    $setValuesUnbound = static function (array $values) use ($object) {
      foreach ($values as $k => $v) {
        $object->$k = $v;
      }
    };

    if ([] !== $values) {
      $setValues = $setValuesUnbound->bindTo(NULL, $reflClass->getName());
      $setValues($values);
    }

    if ([] === $privateParentValues) {
      return $object;
    }

    $i = 0;
    while ($reflClass = $reflClass->getParentClass()) {
      ++$i;
      if (!empty($privateParentValues[$i])) {
        $setValues = $setValuesUnbound->bindTo(NULL, $reflClass->getName());
        $setValues($privateParentValues[$i]);
      }
    }

    return $object;
  }

  /**
   * @param object $object
   * @param string $methodName
   * @param array $args
   * @param string|null $context
   *
   * @return mixed
   *
   * @throws \ReflectionException
   */
  public static function objectCallMethodArgs(object $object, string $methodName, array $args, $context = NULL) {

    if (NULL === $context) {
      /** @var string|object|null $context */
      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      $context = $object;
    }

    $reflMethod = new \ReflectionMethod($context, $methodName);

    $accessible = !$reflMethod->isProtected() && !$reflMethod->isPrivate();

    if (!$accessible) {
      $reflMethod->setAccessible(TRUE);
      $return = $reflMethod->invokeArgs($object, $args);
      $reflMethod->setAccessible(FALSE);
    }
    else {
      $return = $reflMethod->invokeArgs($object, $args);
    }

    return $return;
  }

  /**
   * @param \ReflectionParameter[] $params
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return mixed[]|null
   */
  public static function paramsGetValues(array $params, ParamToValueInterface $paramToValue): ?array {

    $else = new \stdClass();

    $argValues = [];
    foreach ($params as $i => $param) {
      if ($else === $argValue = $paramToValue->paramGetValue($param, $else)) {
        return NULL;
      }
      $argValues[$i] = $argValue;
    }

    return $argValues;
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   * @param mixed $else
   *
   * @return mixed
   *
   * @throws \Exception
   *   Exception from callback.
   */
  public static function callbackInvokePTV(
    CallbackReflectionInterface $callback,
    ParamToValueInterface $paramToValue,
    $else = NULL
  ) {

    $params = $callback->getReflectionParameters();

    if ([] === $params) {
      return $callback->invokeArgs([]);
    }

    $args = self::paramsGetValues(
      $params,
      $paramToValue);

    if (NULL === $args) {
      return $else;
    }

    return $callback->invokeArgs($args);
  }

}
