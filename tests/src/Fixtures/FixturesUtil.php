<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\DID\Container\Container_CTVs;
use Donquixote\DID\Exception\ContainerToValueException;
use Psr\Container\ContainerInterface;

class FixturesUtil {

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

}
