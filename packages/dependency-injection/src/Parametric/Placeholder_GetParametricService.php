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
   * @param array<string|int|PlaceholderInterface> $argumentsMap
   *   Argument lookup map.
   *   If empty, the arguments are just passed through.
   */
  public function __construct(
    private readonly string $parentId,
    private readonly array $argumentsMap,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function needsArguments(): bool {
    if ($this->argumentsMap === []) {
      return true;
    }
    foreach ($this->argumentsMap as $v) {
      if ($v instanceof PlaceholderInterface) {
        if ($v->needsArguments()) {
          return true;
        }
      }
    }
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(array $arguments, ContainerBuilder $container): mixed {
    $mapped_arguments = $this->getMappedArguments($arguments, $container);
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

  /**
   * Builds mapped arguments.
   *
   * @param array $arguments
   *   Original arguments.
   * @param ContainerBuilder $container
   *   Container builder.
   *
   * @return array
   *   Mapped arguments.
   */
  protected function getMappedArguments(array $arguments, ContainerBuilder $container): array {
    if ($this->argumentsMap === []) {
      return $arguments;
    }
    $mapped_arguments = [];
    foreach ($this->argumentsMap as $parent_key => $value) {
      if ($value instanceof PlaceholderInterface) {
        $value = $value->resolve($arguments, $container);
      }
      $mapped_arguments[$parent_key] = $value;
    }
    return $mapped_arguments;
  }

}
