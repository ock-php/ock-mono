<?php

declare(strict_types = 1);

namespace Donquixote\Adaptism\Util;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\DI\ServiceIdHavingInterface;
use Donquixote\Adaptism\Exception\MalformedDeclarationException;

class ServiceAttributesUtil {

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return (string|null)[]
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  public static function paramsGetServiceIds(array $parameters, bool $force = FALSE): array {
    $args = [];
    foreach ($parameters as $key => $parameter) {
      $id = static::paramGetServiceId($parameter, $force);
      if ($id !== NULL) {
        $args[$key] = $id;
      }
    }
    return $args;
  }

  /**
   * @template force as bool
   *
   * @param \ReflectionParameter $parameter
   * @param bool $force
   *   TRUE to throw an exception if no id found, FALSE to return NULL.
   * @psalm-param force $force
   *
   * @return string|null
   * @psalm-return (force is true ? string : (string|null))
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  public static function paramGetServiceId(\ReflectionParameter $parameter, bool $force = FALSE): ?string {
    $id = AttributesUtil::getSingle($parameter, GetService::class)
      ?->getId();
    if ($id !== NULL) {
      return $id;
    }
    $class = $force
      ? ReflectionTypeUtil::requireGetClassLikeType($parameter)
      : ReflectionTypeUtil::getClassLikeType($parameter);
    if ($class === NULL) {
      return NULL;
    }
    try {
      $rClass = new \ReflectionClass($class);
    }
    catch (\ReflectionException $e) {
      throw new MalformedDeclarationException(\sprintf(
        'Unknown class %s in parameter type for %s.',
        $class,
        MessageUtil::formatReflector($parameter),
      ), 0, $e);
    }
    return AttributesUtil::getSingle($rClass, ServiceIdHavingInterface::class)
      ?->getServiceId()
      ?? $class;
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return string[]
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  public static function paramsRequireServiceIds(array $parameters): array {
    return \array_map([static::class, 'paramRequireServiceId'], $parameters);
  }

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  public static function paramRequireServiceId(\ReflectionParameter $parameter): string {
    return static::paramGetServiceId($parameter, TRUE);
  }

}
