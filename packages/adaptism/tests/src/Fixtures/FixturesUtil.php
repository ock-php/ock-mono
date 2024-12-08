<?php
declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures;

use Ock\Adaptism\AdaptismPackage;
use Ock\DependencyInjection\Provider\CommonServiceProvider;
use Ock\Egg\EggPackage;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FixturesUtil {

  /**
   * Builds a container with services for adaptism tests.
   *
   * @return \Symfony\Component\DependencyInjection\ContainerBuilder New container.
   *   New container.
   */
  public static function getContainer(): ContainerBuilder {
    $container = new ContainerBuilder();
    (new CommonServiceProvider())->register($container);
    (new EggPackage())->register($container);
    (new AdaptismPackage())->register($container);
    (new AdaptismTestPackage())->register($container);

    $container->compile();

    return $container;
  }

}
