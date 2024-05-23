<?php

declare(strict_types = 1);

namespace Drupal\ock\DI\ServiceProvider;

use Ock\ClassDiscovery\Shared\ReflectionClassesIAHavingBase;
use Ock\DID\Attribute\Service;
use Ock\DID\Util\AttributesUtil;
use Ock\DID\Util\ReflectionTypeUtil;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Drupal\ock\Attribute\DI\ServiceTags;
use Drupal\ock\Attribute\RequireModules;
use Drupal\ock\DI\ContainerArgumentExpression;
use Drupal\ock\DI\ContainerExpressionUtil;
use Drupal\ock\DI\ParamToServiceArg\ParamToServiceArg_Attribute_CallService;
use Drupal\ock\DI\ParamToServiceArg\ParamToServiceArg_Attribute_CallServiceMethod;
use Drupal\ock\DI\ParamToServiceArg\ParamToServiceArg_Attribute_GetContainerParameter;
use Drupal\ock\DI\ParamToServiceArg\ParamToServiceArg_Attribute_GetService;
use Drupal\ock\DI\ParamToServiceArg\ParamToServiceArg_Chain;
use Drupal\ock\DI\ParamToServiceArg\ParamToServiceArgInterface;
use Symfony\Component\DependencyInjection\Definition;

class ServiceProvider_AttributesDiscovery extends ReflectionClassesIAHavingBase implements ServiceProviderInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\ock\DI\ParamToServiceArg\ParamToServiceArgInterface $paramToServiceArg
   */
  public function __construct(
    private readonly ParamToServiceArgInterface $paramToServiceArg,
  ) {}

  /**
   * Static factory with default arguments.
   *
   * @return self
   */
  public static function create(): self {
    return new self(new ParamToServiceArg_Chain([
      new ParamToServiceArg_Attribute_GetService(),
      new ParamToServiceArg_Attribute_CallService(),
      new ParamToServiceArg_Attribute_CallServiceMethod(),
      new ParamToServiceArg_Attribute_GetContainerParameter(),
    ]));
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Ock\DID\Exception\DiscoveryException
   */
  public function register(ContainerBuilder $container): void {
    $installedModulesMap = $container->getParameter('container.modules');
    $definitions = [];
    /** @var \ReflectionClass $rClass */
    foreach ($this->itReflectionClasses() as $rClass) {
      if ($rClass->isTrait() || $rClass->isInterface()) {
        // Service declarations not allowed on this class or its methods.
        continue;
      }
      /** @var \Drupal\ock\Attribute\RequireModules $attribute */
      foreach (AttributesUtil::getAll($rClass, RequireModules::class) as $attribute) {
        foreach ($attribute->modules as $module) {
          if (!isset($installedModulesMap[$module])) {
            continue 3;
          }
        }
      }
      if ($rClass->isInstantiable()) {
        foreach (AttributesUtil::getAll($rClass, Service::class) as $attribute) {
          assert($attribute instanceof Service);
          $def = $attribute->onClass($rClass);
          $id = $def->id;
          $definition = $this->onClass($rClass);
          $this->decorate($definition, $rClass);
          $container->setDefinition($id, $definition);
          $definitions[$id] = $definition;
        }
      }
      foreach ($rClass->getMethods() as $rMethod) {
        if ($rMethod->getDeclaringClass()->getName() !== $rClass->getName()
          || !$rMethod->isStatic()
          || !$rMethod->isPublic()
          || $rMethod->isAbstract()
        ) {
          continue;
        }
        /** @var Service $attribute */
        foreach (AttributesUtil::getAll($rMethod, Service::class) as $attribute) {
          $id = $attribute->onMethod($rMethod)->id;
          $definition = $this->onMethod($rMethod);
          $this->decorate($definition, $rMethod);
          $container->setDefinition($id, $definition);
          $definitions[$id] = $definition;
        }
      }
    }
  }

  /**
   * @param \Symfony\Component\DependencyInjection\Definition $definition
   * @param \ReflectionClass|\ReflectionMethod $reflector
   */
  private function decorate(
    Definition $definition,
    \ReflectionClass|\ReflectionMethod $reflector,
  ): void {
    /** @var \Drupal\ock\Attribute\DI\ServiceTags $attribute */
    foreach (AttributesUtil::getAll($reflector, ServiceTags::class) as $attribute) {
      foreach ($attribute->tags as $tag) {
        $definition->addTag($tag);
      }
    }
  }

  /**
   * @param \ReflectionClass $rClass
   *
   * @return \Symfony\Component\DependencyInjection\Definition
   *
   * @throws \Ock\DID\Exception\DiscoveryException
   */
  private function onClass(\ReflectionClass $rClass): Definition {
    $definition = new Definition();
    $definition->setClass($rClass->getName());
    $parameters = $rClass->getConstructor()?->getParameters();
    if (!$parameters) {
      return $definition;
    }
    $ops = [];
    $arguments = $this->buildArguments($parameters, $ops);
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
   * @throws \Ock\DID\Exception\DiscoveryException
   */
  private function onMethod(\ReflectionMethod $rMethod): Definition {
    $rDeclaringClass = $rMethod->getDeclaringClass();
    $returnTypeName = ReflectionTypeUtil::requireGetClassLikeType($rMethod);
    $definition = new Definition();
    $definition->setClass($returnTypeName);
    $definition->setFactory(
      [$rDeclaringClass->getName(), $rMethod->getName()],
    );
    $parameters = $rMethod->getParameters();
    if (!$parameters) {
      return $definition;
    }
    $ops = [];
    $arguments = $this->buildArguments($parameters, $ops);
    if (!$ops) {
      $definition->setArguments($arguments);
      return $definition;
    }
    // Special acrobatics is required to allow for expressions in Drupal.
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
   * @param array $parameters
   * @param array $ops
   *
   * @return array
   *
   * @throws \Ock\DID\Exception\DiscoveryException
   */
  private function buildArguments(array $parameters, array &$ops): array {
    $args = [];
    foreach ($parameters as $i => $parameter) {
      $arg = $this->paramToServiceArg->paramGetServiceArg($parameter);
      if ($arg instanceof ContainerArgumentExpression) {
        $ops[$i] = $arg->getOp();
        $arg = $arg->getValue();
      }
      $args[$i] = $arg;
    }
    return $args;
  }

}
