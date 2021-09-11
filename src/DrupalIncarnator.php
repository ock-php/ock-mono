<?php

declare(strict_types=1);

namespace Drupal\cu;

use Donquixote\ObCK\Incarnator\IncarnatorInterface;
use Drupal\cu\Util\UtilBase;
use Psr\Container\ContainerInterface;

/**
 * Static methods for incarnators with a Drupal perspective.
 */
class DrupalIncarnator extends UtilBase {

  /**
   * @param \Psr\Container\ContainerInterface|null $container
   *
   * @return \Donquixote\ObCK\Incarnator\IncarnatorInterface
   */
  public static function fromContainer(ContainerInterface $container = NULL): IncarnatorInterface {
    if (!$container) {
      $container = \Drupal::getContainer();
    }
    $instance = $container->get(IncarnatorInterface::class);
    if (!$instance) {
      throw new \RuntimeException('Service not found.');
    }
    if (!$instance instanceof IncarnatorInterface) {
      throw new \RuntimeException('Service has unexpected type.');
    }
    return $instance;
  }

}
