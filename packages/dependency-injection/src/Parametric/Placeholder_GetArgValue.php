<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Parametric;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class Placeholder_GetArgValue implements PlaceholderInterface {

  public function __construct(
    private readonly string|int $key,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getRequiredKeys(): array {
    return [$this->key];
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(array $arguments, ContainerBuilder $container): mixed {
    return $arguments[$this->key]
      // @todo Support NULL value?
      ?? throw new \RuntimeException("Missing argument value for '$this->key'.");
  }

}
