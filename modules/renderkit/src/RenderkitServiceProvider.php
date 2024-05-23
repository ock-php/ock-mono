<?php
declare(strict_types=1);

namespace Drupal\renderkit;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Drupal\ock\DI\ServiceProvider\ServiceProvider_AttributesDiscovery;
use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIA;

/**
 * Class with static methods for easier construction of display handlers.
 */
class RenderkitServiceProvider implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   *   Malformed service declaration.
   */
  public function register(ContainerBuilder $container): void {
    ServiceProvider_AttributesDiscovery::create()
      ->withClassFilesIA(ClassFilesIA::psr4FromClass(self::class))
      ->register($container);
  }

}
