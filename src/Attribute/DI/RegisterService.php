<?php

declare(strict_types=1);

namespace Drupal\ock\Attribute\DI;

use Donquixote\Adaptism\DI\ServiceIdHavingInterface;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\ock\Util\ServiceDiscoveryUtil;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class RegisterService implements ServiceProviderAttributeInterface, ServiceIdHavingInterface {

  /**
   * Constructor.
   *
   * @param string|null $name
   *   Name of the service, or NULL to use interface name.
   */
  public function __construct(
    private readonly ?string $name = NULL,
    private readonly array $modules = [],
  ) {}

  /**
   * {@inheritdoc}
   */
  public function register(
    ContainerBuilder $container,
    \ReflectionMethod|\ReflectionClass $reflector,
  ): void {
    if ($this->modules) {
      $installedModulesMap = $container->getParameter('container.modules');
      foreach ($this->modules as $requiredModule) {
        if (!isset($installedModulesMap[$requiredModule])) {
          return;
        }
      }
    }
    if ($reflector instanceof \ReflectionMethod) {
      $definition = ServiceDiscoveryUtil::buildStaticFactory($reflector);
    }
    else {
      $definition = ServiceDiscoveryUtil::buildClass($reflector);
    }
    $container->setDefinition(
      $this->name ?? $definition->getClass(),
      $definition,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getServiceId(): ?string {
    return $this->name;
  }

}
