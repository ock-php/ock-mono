<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class GetValue implements DependencyInjectionArgumentInterface {

  /**
   * Constructor.
   *
   * @param mixed $value
   */
  public function __construct(
    private readonly mixed $value,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getArgDefinition(\ReflectionParameter $parameter): mixed {
    return $this->value;
  }

  /**
   * {@inheritdoc}
   */
  public function paramGetValue(\ReflectionParameter $parameter, ContainerInterface $container): mixed {
    return $this->value;
  }

}
