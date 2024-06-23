<?php
declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures;

use Ock\Adaptism\AdaptismPackage;
use Ock\DependencyInjection\Provider\CommonServiceProvider;
use Ock\Egg\EggNamespace;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class FixturesUtil {

  /**
   * @var \Psr\Container\ContainerInterface|null
   */
  private static ?ContainerInterface $container;

  /**
   * Builds a container with services for adaptism tests.
   *
   * @return \Psr\Container\ContainerInterface
   *   New container.
   */
  public static function getContainer(): ContainerInterface {
    return self::$container ??= self::buildContainer();
  }

  /**
   * @return \Psr\Container\ContainerInterface
   */
  private static function buildContainer(): ContainerInterface {
    $container = new ContainerBuilder();
    (new CommonServiceProvider())->register($container);
    static::loadPackageServicesPhp($container, dirname(EggNamespace::DIR));
    static::loadPackageServicesPhp($container, dirname(AdaptismPackage::DIR));
    static::loadPackageServicesPhp($container, dirname(__DIR__, 2), 'services.test.php');
    $container->setAlias(ContainerInterface::class, 'service_container');

    $container->compile();

    return $container;
  }

  /**
   * Loads services from a services.php in a package directory.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   * @param string $dir
   *   Directory where the services.php is found.
   * @param string $file
   *   File name.
   */
  protected static function loadPackageServicesPhp(ContainerBuilder $container, string $dir, string $file = 'services.php'): void {
    $locator = new FileLocator($dir);
    $loader = new PhpFileLoader($container, $locator);
    try {
      $loader->load($file);
    }
    catch (\Exception $e) {
      // A service definition file is broken or missing.
      // This must be a programming error in this package.
      // Convert to unhandled exception type.
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }
  }

}
