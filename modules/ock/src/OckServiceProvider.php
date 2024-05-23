<?php

declare(strict_types = 1);

namespace Drupal\ock;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Drupal\ock\DI\ServiceProvider\ServiceProvider_AttributesDiscovery;
use Ock\Adaptism\AdaptismPackage;
use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Ock\Ock\OckPackage;

class OckServiceProvider implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   *   Malformed service declaration.
   */
  public function register(ContainerBuilder $container): void {
    ServiceProvider_AttributesDiscovery::create()
      ->withClassFilesIA(ClassFilesIA::psr4FromClasses([
        AdaptismPackage::class,
        OckPackage::class,
        self::class,
      ]))
      ->register($container);
  }

}
