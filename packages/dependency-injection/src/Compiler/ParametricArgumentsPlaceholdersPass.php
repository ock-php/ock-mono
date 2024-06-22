<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Compiler;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument;
use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Parametric\Placeholder_GetArgValue;
use Ock\DependencyInjection\Parametric\Placeholder_GetParametricService;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Inserts placeholders for parametric arguments.
 */
class ParametricArgumentsPlaceholdersPass implements CompilerPassInterface {

  public function process(ContainerBuilder $container): void {
    $container->registerAttributeForAutoconfiguration(
      GetParametricArgument::class,
      static function(
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
          throw new \RuntimeException('The delta must be provided if this is not the first parameter.');
        }
        $placeholder = new Placeholder_GetArgValue($delta);
        $definition->setArgument($parameter->getPosition(), $placeholder);
      },
    );
    $container->registerAttributeForAutoconfiguration(
      GetParametricService::class,
      static function(
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
      },
    );
  }

}
