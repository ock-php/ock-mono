<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

use Donquixote\Ock\Util\PhpUtil;
use Drupal\ock\DI\ContainerArgumentExpression;
use Drupal\ock\DI\ContainerExpressionUtil;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;

#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class DrupalServiceCall implements DependencyInjectionArgumentInterface {

  /**
   * Constructor.
   *
   * @param string $serviceId
   *   Service name.
   * @param array $args
   *   Argument values for the call.
   */
  public function __construct(
    private readonly string $serviceId,
    private readonly array $args,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getArgDefinition(\ReflectionParameter $parameter): ContainerArgumentExpression {
    return new ContainerArgumentExpression(
      ContainerExpressionUtil::OP_CALL,
      [
        'callback' => new Reference($this->serviceId),
        'args' => $this->args,
      ],
    );
  }

  /**
   * {@inheritdoc}
   */
  public function paramGetValue(\ReflectionParameter $parameter, ContainerInterface $container): mixed {
    return $container->get($this->serviceId)(...$this->args);
  }

}
