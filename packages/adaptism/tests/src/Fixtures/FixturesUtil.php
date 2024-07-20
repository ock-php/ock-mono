<?php
declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures;

use Ock\Adaptism\AdaptismPackage;
use Ock\DependencyInjection\Provider\CommonServiceProvider;
use Ock\Egg\EggPackage;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class FixturesUtil {

  /**
   * @var \Symfony\Component\DependencyInjection\ContainerBuilder
   */
  private static ContainerBuilder $container;

  /**
   * Builds a container with services for adaptism tests.
   *
   * @return \Symfony\Component\DependencyInjection\ContainerBuilder New container.
   *   New container.
   */
  public static function getContainer(): ContainerBuilder {
    return self::$container ??= self::buildContainer();
  }

  /**
   * @return \Symfony\Component\DependencyInjection\ContainerBuilder
   */
  private static function buildContainer(): ContainerBuilder {
    $container = new ContainerBuilder();
    (new CommonServiceProvider())->register($container);
    (new EggPackage())->register($container);
    (new AdaptismPackage())->register($container);
    (new AdaptismTestPackage())->register($container);

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
