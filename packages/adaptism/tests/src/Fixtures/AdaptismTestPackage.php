<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

class AdaptismTestPackage {

  /**
   * Configures a symfony container.
   *
   * This is called from services.test.php.
   *
   * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
   */
  public static function configureServices(ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
      ->autowire()
      ->autoconfigure();

    // The path is relative to the directory where 'services.php' is located.
    $services->load(__NAMESPACE__ . '\\', 'src/Fixtures/');

    $services->set(\DateTimeZone::class)
      ->class(\DateTimeZone::class)
      ->public()
      ->arg(0, 'America/New_York');
  }

}
