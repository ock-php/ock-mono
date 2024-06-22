<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Parametric;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class Placeholder_GetParametricService implements PlaceholderInterface {

  /**
   * Constructor.
   *
   * @param string $parentId
   *   Id of an abstract service.
   * @param array<string|int, string|int|PlaceholderInterface> $argumentsMap
   *   Argument lookup map.
   */
  public function __construct(
    private readonly string $parentId,
    private readonly array $argumentsMap,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getRequiredKeys(): array {
    $keys = [];
    foreach ($this->argumentsMap as $v) {
      if ($v instanceof PlaceholderInterface) {
        $keys = [...$keys, ...$v->getRequiredKeys()];
      }
    }
    return \array_unique($keys);
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(array $arguments, ContainerBuilder $container): mixed {
    $mapped_arguments = [];
    foreach ($this->argumentsMap as $parent_key => $value) {
      if ($value instanceof PlaceholderInterface) {
        $value = $value->resolve($arguments, $container);
      }
      $mapped_arguments[$parent_key] = $value;
    }
    $parent_definition = $container->getDefinition($this->parentId);
    $parent_args = $parent_definition->getArguments();
    $resolved_args = [];
    foreach ($parent_args as $key => $arg) {
      if ($arg instanceof PlaceholderInterface) {
        $arg = $arg->resolve($mapped_arguments, $container);
      }
      $resolved_args[$key] = $arg;
    }
    $arg_definition = clone $parent_definition;
    $arg_definition->setArguments($resolved_args);
    return $arg_definition;
  }

}
