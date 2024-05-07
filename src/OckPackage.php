<?php

declare(strict_types=1);

namespace Donquixote\Ock;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\Discovery\DiscoveryInterface;
use Donquixote\ClassDiscovery\Discovery\ObjectDiscovery;
use Donquixote\DID\ClassToCTV\ClassToCTV_Construct;
use Donquixote\DID\ClassToCTV\ClassToCTVInterface;
use Donquixote\DID\ParamToCTV\ParamToCTV_Chain;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

class OckPackage {

  const DISCOVERY_TARGET = 'ockDiscovery';

  const DISCOVERY_TAG_NAME = 'ock.discovery';

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

    $services->set(ClassToCTVInterface::class)
      ->class(ClassToCTV_Construct::class);

    $services->set(ParamToCTVInterface::class)
      ->class(ParamToCTV_Chain::class);
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   * @throws \ReflectionException
   */
  public function getServiceDiscoveryNamespaces(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClass(self::class);
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   * @throws \ReflectionException
   */
  public function getAdapterDiscoveryNamespaces(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClass(self::class);
  }

}
