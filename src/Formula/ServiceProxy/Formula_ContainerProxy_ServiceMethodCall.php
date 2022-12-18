<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Formula\ServiceProxy;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Psr\Container\ContainerInterface;

class Formula_ContainerProxy_ServiceMethodCall implements Formula_ContainerProxyInterface {

  public function __construct(
    private readonly string $serviceId,
    private readonly string $method,
    private readonly array $args = [],
  ) {}

  /**
   * {@inheritdoc}
   */
  public function containerGetFormula(ContainerInterface $container): FormulaInterface {
    return $container->get($this->serviceId)->{$this->method}(...$this->args);
  }

}
