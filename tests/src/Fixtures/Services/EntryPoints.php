<?php

declare(strict_types = 1);

namespace Donquixote\Adaptism\Tests\Fixtures\Services;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\DID\Container\Container_CTVs;
use Donquixote\DID\Exception\ContainerToValueException;
use Donquixote\DID\ServiceDefinitionList\ServiceDefinitionList_Discovery;
use Donquixote\DID\ServiceDefinitionList\ServiceDefinitionListInterface;
use Psr\Container\ContainerInterface;

class EntryPoints {

  /**
   * @var \Psr\Container\ContainerInterface|null
   */
  private static ?ContainerInterface $container;

  /**
   * @return \Psr\Container\ContainerInterface
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  public static function getContainer(): ContainerInterface {
    return self::$container ??= self::buildContainer();
  }

  /**
   * @return \Psr\Container\ContainerInterface
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private static function buildContainer(): ContainerInterface {
    try {
      return Container_CTVs::fromClassFilesIAs([
        ClassFilesIA::psr4FromClass(self::class),
        ClassFilesIA::psr4FromClass(UniversalAdapterInterface::class, 1),
      ]);
    }
    catch (\ReflectionException $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
  }

  public static function getServiceDefinitionList(): ServiceDefinitionListInterface {
    return (new ServiceDefinitionList_Discovery())
      ->withClassFilesIA(self::getServicesClassFilesIA());
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   *
   * @throws \ReflectionException
   */
  public static function getServicesClassFilesIA(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClass(self::class);
  }

}
