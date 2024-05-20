<?php

declare(strict_types = 1);

namespace Ock\Adaptism;

use Ock\ClassDiscovery\Discovery\DiscoveryInterface;
use Ock\ClassDiscovery\Discovery\FactoryDiscovery;
use Ock\Egg\ClassToEgg\ClassToEgg_Construct;
use Ock\Egg\ClassToEgg\ClassToEggInterface;
use Ock\Egg\ParamToEgg\ParamToEgg_Chain;
use Ock\Egg\ParamToEgg\ParamToEggInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

class AdaptismPackage {

  const DIR = __DIR__;

  const DISCOVERY_TARGET = 'adaptismDiscovery';

  const DISCOVERY_TAG_NAME = 'adaptism.discovery';

  /**
   * Configures a symfony container.
   *
   * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
   */
  public static function configureServices(ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
      ->autowire()
      ->autoconfigure();

    // The path is relative to the package root where 'services.php' is located.
    $services->load(__NAMESPACE__ . '\\', 'src/');

    $services->set(DiscoveryInterface::class . ' $' . self::DISCOVERY_TARGET)
      ->public()
      ->class(FactoryDiscovery::class)
      ->factory([FactoryDiscovery::class, 'fromCandidateObjects'])
      ->arg(0, tagged_iterator(self::DISCOVERY_TAG_NAME));

    $services->set(ClassToEggInterface::class)
      ->class(ClassToEgg_Construct::class);

    $services->set(ParamToEggInterface::class)
      ->class(ParamToEgg_Chain::class);
  }

}
