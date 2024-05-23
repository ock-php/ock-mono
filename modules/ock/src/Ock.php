<?php

declare(strict_types=1);

namespace Drupal\ock;

use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Static access to some essential services.
 */
class Ock {

  /**
   * Gets the universal adapter service.
   *
   * @return \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface
   *   The service.
   */
  public static function adapter(): UniversalAdapterInterface {
    return \Drupal::service(UniversalAdapterInterface::class);
  }

}
