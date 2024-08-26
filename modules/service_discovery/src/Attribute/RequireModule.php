<?php

declare(strict_types=1);

namespace Drupal\service_discovery\Attribute;

use Ock\DependencyInjection\Attribute\ServiceConditionAttributeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Skip services from this class or method, if a given module is not installed.
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
class RequireModule implements ServiceConditionAttributeInterface {

  /**
   * Constructor.
   *
   * @param string $module
   *   The module without which the class or method should be skipped.
   */
  public function __construct(
    private readonly string $module,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function check(ContainerBuilder $container): bool {
    $container_modules = $container->getParameter('container.modules');
    return \in_array($this->module, $container_modules);
  }

}
