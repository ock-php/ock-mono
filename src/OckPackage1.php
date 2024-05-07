<?php

declare(strict_types=1);

namespace Donquixote\Ock;

use Donquixote\Adaptism\Inspector\AdapterClassInspector;
use Donquixote\Adaptism\Inspector\SelfAdapterClassInspector;
use Donquixote\ClassDiscovery\Discovery\DiscoveryInterface;
use Donquixote\ClassDiscovery\Discovery\ObjectDiscovery;
use Donquixote\DID\ClassToCTV\ClassToCTV_Construct;
use Donquixote\DID\ClassToCTV\ClassToCTVInterface;
use Donquixote\DID\ParamToCTV\ParamToCTV_Chain;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

class OckPackage1 {

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
      ->class(ObjectDiscovery::class)
      ->factory([ObjectDiscovery::class, 'fromHandlers'])
      ->arg(0, tagged_iterator(self::DISCOVERY_TAG_NAME));

    $services->set(AdapterClassInspector::class)
      ->tag(self::DISCOVERY_TAG_NAME);

    $services->set(SelfAdapterClassInspector::class)
      ->tag(self::DISCOVERY_TAG_NAME);

    $services->set(ClassToCTVInterface::class)
      ->class(ClassToCTV_Construct::class);

    $services->set(ParamToCTVInterface::class)
      ->class(ParamToCTV_Chain::class);
  }

}
