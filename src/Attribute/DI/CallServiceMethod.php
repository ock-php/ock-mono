<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

use Drupal\ock\DI\ContainerArgumentExpression;
use Drupal\ock\DI\ContainerExpressionUtil;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class CallServiceMethod implements DependencyInjectionArgumentInterface  {

  /**
   * Constructor.
   *
   * @param string $serviceId
   *   Service name.
   * @param string $method
   *   Method name.
   * @param array $args
   *   Argument values for the call.
   */
  public function __construct(
    private readonly string $serviceId,
    private readonly string $method,
    private readonly array $args,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getArgDefinition(\ReflectionParameter $parameter): ContainerArgumentExpression {
    return new ContainerArgumentExpression(
      ContainerExpressionUtil::OP_METHOD,
      [
        'object' => new Reference($this->serviceId),
        'method' => $this->method,
        'args' => $this->args,
      ],
    );
  }

  /**
   * {@inheritdoc}
   */
  public function paramGetValue(\ReflectionParameter $parameter, ContainerInterface $container): mixed {
    return $container->get($this->serviceId)->{$this->method}(...$this->args);
  }

}
