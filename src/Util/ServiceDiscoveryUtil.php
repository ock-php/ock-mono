<?php

declare(strict_types=1);

namespace Drupal\ock\Util;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\DI\ServiceIdHavingInterface;
use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\Adaptism\Util\MessageUtil;
use Donquixote\Adaptism\Util\ReflectionTypeUtil;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\ock\Attribute\DI\DependencyInjectionArgumentInterface;
use Drupal\ock\Attribute\DI\ServiceProviderAttributeInterface;
use Drupal\ock\DI\ContainerArgumentExpression;
use Drupal\ock\DI\ContainerExpressionUtil;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @todo This should be a service, not a utility class.
 */
class ServiceDiscoveryUtil {

  /**
   * @param \Drupal\Core\DependencyInjection\ContainerBuilder $container
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFiles
   *
   * @throws \Exception
   */
  public static function discoverInClassFiles(ContainerBuilder $container, ClassFilesIAInterface $classFiles): void {
    /** @var class-string $class */
    foreach ($classFiles as $class) {
      static::discoverInClass($container, $class);
    }
  }

  /**
   * @param \Drupal\Core\DependencyInjection\ContainerBuilder $container
   * @param class-string $class
   *
   * @throws \Exception
   */
  public static function discoverInClass(ContainerBuilder $container, string $class): void {
    $rClass = new \ReflectionClass($class);
    /** @var ServiceProviderAttributeInterface $instance */
    foreach (AttributesUtil::getInstances($rClass, ServiceProviderAttributeInterface::class) as $instance) {
      $instance->register($container, $rClass);
    }
    foreach ($rClass->getMethods() as $rMethod) {
      if ($rMethod->getDeclaringClass()->getName() !== $rClass->getName()) {
        continue;
      }
      foreach (AttributesUtil::getInstances($rMethod, ServiceProviderAttributeInterface::class) as $instance) {
        $instance->register($container, $rMethod);
      }
    }
  }

  /**
   * @param \ReflectionMethod $rMethod
   *
   * @return \Symfony\Component\DependencyInjection\Definition
   *
   * @throws \Exception
   */
  public static function buildStaticFactory(\ReflectionMethod $rMethod): Definition {
    $parameters = $rMethod->getParameters();
    $definition = static::buildStaticFactoryStub($rMethod);
    if (!$parameters) {
      return $definition;
    }
    $ops = [];
    $arguments = static::buildArguments($parameters, $ops);
    if (!$ops) {
      $definition->setArguments($arguments);
      return $definition;
    }
    $definition->setArguments([
      $definition->getFactory(),
      $arguments,
      $ops,
    ]);
    $definition->setFactory(
      [ContainerExpressionUtil::class, 'call'],
    );
    return $definition;
  }

  /**
   * @param \ReflectionClass $rClass
   *
   * @return \Symfony\Component\DependencyInjection\Definition
   * @throws \Exception
   */
  public static function buildClass(\ReflectionClass $rClass): Definition {
    $parameters = $rClass->getConstructor()?->getParameters();
    $definition = static::buildClassStub($rClass);
    if (!$parameters) {
      return $definition;
    }
    $ops = [];
    $arguments = static::buildArguments($parameters, $ops);
    if (!$ops) {
      $definition->setArguments($arguments);
      return $definition;
    }
    $definition->setFactory(
      [ContainerExpressionUtil::class, 'construct'],
    );
    $definition->setArguments([
      $rClass->getName(),
      $arguments,
      $ops,
    ]);
    return $definition;
  }

  /**
   * @param \ReflectionMethod $rMethod
   *
   * @return \Symfony\Component\DependencyInjection\Definition
   *
   * @throws \Exception
   */
  public static function buildStaticFactoryStub(\ReflectionMethod $rMethod): Definition {
    if ($rMethod->isAbstract()) {
      throw new \Exception(\sprintf(
        'Service declaration not allowed on abstract method: %s.',
        MessageUtil::formatReflector($rMethod),
      ));
    }
    if (!$rMethod->isPublic()) {
      throw new \Exception(\sprintf(
        'Service declaration not allowed on non-public method: %s.',
        MessageUtil::formatReflector($rMethod),
      ));
    }
    if ($rMethod->isConstructor()) {
      throw new \Exception(\sprintf(
        'Service declaration not allowed on constructor: %s.',
        MessageUtil::formatReflector($rMethod),
      ));
    }
    if (!$rMethod->isStatic()) {
      throw new \Exception(\sprintf(
        'Service declaration not allowed on non-static method: %s.',
        MessageUtil::formatReflector($rMethod),
      ));
    }
    $rDeclaringClass = $rMethod->getDeclaringClass();
    if ($rDeclaringClass->isTrait()) {
      throw new \Exception(\sprintf(
        'Service declaration not allowed on trait method: %s.',
        MessageUtil::formatReflector($rMethod),
      ));
    }
    if ($rDeclaringClass->isInterface()) {
      throw new \Exception(\sprintf(
        'Service declaration not allowed on interface method: %s.',
        MessageUtil::formatReflector($rMethod),
      ));
    }
    $returnTypeName = ReflectionTypeUtil::requireGetClassLikeType($rMethod);
    $definition = new Definition();
    $definition->setClass($returnTypeName);
    $definition->setFactory(
      [$rDeclaringClass->getName(), $rMethod->getName()],
    );
    return $definition;
  }

  /**
   * @param \ReflectionClass $rClass
   *
   * @return \Symfony\Component\DependencyInjection\Definition
   * @throws \Exception
   */
  public static function buildClassStub(\ReflectionClass $rClass): Definition {
    if ($rClass->isInterface()) {
      throw new \Exception(\sprintf(
        'Service declaration not allowed on interface: %s.',
        MessageUtil::formatReflector($rClass),
      ));
    }
    if ($rClass->isTrait()) {
      throw new \Exception(\sprintf(
        'Service declaration not allowed on trait: %s.',
        MessageUtil::formatReflector($rClass),
      ));
    }
    if ($rClass->isAbstract()) {
      throw new \Exception(\sprintf(
        'Service declaration not allowed on abstract class: %s.',
        MessageUtil::formatReflector($rClass),
      ));
    }
    $definition = new Definition();
    $definition->setClass($rClass->getName());
    return $definition;
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return mixed[]
   *   Arguments definition.
   *
   * @throws \Exception
   */
  public static function buildArguments(array $parameters, array &$ops): array {
    $args = [];
    foreach ($parameters as $i => $parameter) {
      $arg = self::buildArgument($parameter);
      if ($arg instanceof ContainerArgumentExpression) {
        $ops[$i] = $arg->getOp();
        $arg = $arg->getValue();
      }
      $args[$i] = $arg;
    }
    return $args;
  }

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return mixed|\Symfony\Component\DependencyInjection\Reference
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   * @throws \ReflectionException
   */
  public static function buildArgument(\ReflectionParameter $parameter): mixed {
    if (NULL !== $instance = AttributesUtil::getSingle($parameter, DependencyInjectionArgumentInterface::class)) {
      return $instance->getArgDefinition($parameter);
    }
    if (NULL !== $id = AttributesUtil::getSingle($parameter, GetService::class)?->getId()) {
      return new Reference($id);
    }
    if (NULL !== $class = ReflectionTypeUtil::getClassLikeType($parameter)) {
      $rc = new \ReflectionClass($class);
      if (NULL !== $id = AttributesUtil::getSingle($rc, ServiceIdHavingInterface::class)?->getServiceId()) {
        return new Reference($id);
      }
      return new Reference($class);
    }
    throw new \Exception(\sprintf(
      'Cannot get argument definition for %s.',
      MessageUtil::formatReflector($parameter),
    ));
  }

}
