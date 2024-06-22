<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument;
use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Compiler\ResolveParametricArgumentsPass;
use Ock\DependencyInjection\Parametric\Placeholder_GetArgValue;
use Ock\DependencyInjection\Parametric\Placeholder_GetParametricService;
use Ock\Helpers\Util\MessageUtil;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceProvider_ParametricServices implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container): void {
    $container->registerAttributeForAutoconfiguration(
      GetParametricArgument::class,
      $this->insertParametricArgumentPlaceholder(...),
    );
    $container->registerAttributeForAutoconfiguration(
      GetParametricService::class,
      $this->insertParametricServicePlaceholder(...),
    );
    $container->addCompilerPass(
      new ResolveParametricArgumentsPass(),
      PassConfig::TYPE_OPTIMIZE,
      -10,
    );
  }

  /**
   * Callback registered for a parameter attribute.
   *
   * @param \Symfony\Component\DependencyInjection\ChildDefinition $definition
   * @param \Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument $attribute
   * @param \Reflector $parameter
   */
  public function insertParametricArgumentPlaceholder(
    ChildDefinition $definition,
    GetParametricArgument $attribute,
    \Reflector $parameter,
  ): void {
    if (!$parameter instanceof \ReflectionParameter) {
      // Ignore promoted property.
      return;
    }
    if ($attribute->delta !== null) {
      $delta = $attribute->delta;
    }
    elseif ($parameter->getPosition() === 0) {
      $delta = 0;
    }
    else {
      throw new \RuntimeException(sprintf(
        'The delta must be provided in attribute %s on %s, if this is not the first parameter.',
        \get_class($attribute),
        MessageUtil::formatReflector($parameter),
      ));
    }
    $placeholder = new Placeholder_GetArgValue($delta);
    $definition->setArgument($parameter->getPosition(), $placeholder);
  }

  /**
   * Callback registered for a parameter attribute.
   *
   * @param \Symfony\Component\DependencyInjection\ChildDefinition $definition
   * @param \Ock\DependencyInjection\Attribute\Parameter\GetParametricService $attribute
   * @param \Reflector $parameter
   */
  public function insertParametricServicePlaceholder(
    ChildDefinition $definition,
    GetParametricService $attribute,
    \Reflector $parameter,
  ): void {
    if (!$parameter instanceof \ReflectionParameter) {
      // Ignore promoted property.
      return;
    }
    $type = $parameter->getType();
    if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
      return;
    }
    $class = $type->getName();
    $args = [];
    foreach ($attribute->args as $key => $value) {
      if ($value instanceof GetParametricArgument) {
        $delta = $value->delta
          ?? ($key === 0)
          ? 0
          : throw new \RuntimeException('A delta must be provided if this is not the first argument.');
        $value = new Placeholder_GetArgValue($delta);
      }
      elseif (!is_string($value) && !is_int($value)) {
        throw new \RuntimeException('Invalid argument value.');
      }
      $args[$key] = $value;
    }
    // @todo Should the id have a prefix like 'parametric.'?
    $parent_id = $class;
    $placeholder = new Placeholder_GetParametricService($parent_id, $args);
    $definition->setArgument($parameter->getPosition(), $placeholder);
  }

}
