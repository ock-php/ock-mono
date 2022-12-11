<?php

declare(strict_types = 1);

namespace Drupal\ock;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Drupal\ock\DI\OckCallbackResolver;
use Drupal\renderkit\TextLookup\TextLookup_EntityId;
use Drupal\ock\Util\ServiceDiscoveryUtil;

class OckServiceProvider implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   *   Malformed service declaration.
   */
  public function register(ContainerBuilder $container): void {
    ServiceDiscoveryUtil::discoverInClassFiles(
      $container,
      ClassFilesIA::psr4FromClasses([
        OckCallbackResolver::class,
      ]),
    );
    ServiceDiscoveryUtil::discoverInClass(
      $container,
      OckServiceFactories::class,
    );
  }

}
