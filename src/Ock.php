<?php

declare(strict_types=1);

namespace Drupal\ock;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Static access to some essential services.
 */
class Ock {

  /**
   * Gets the universal adapter service.
   *
   * @return \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface
   *   The service.
   */
  public static function adapter(): UniversalAdapterInterface {
    return static::service(UniversalAdapterInterface::class);
  }

  /**
   * Get a service that implements an interface.
   *
   * @template T as object
   *
   * @param class-string<T> $interface
   *   The interface.
   * @param string|null $id
   *   The service id, or NULL to use the interface as the service id.
   *
   * @return T
   *   Service.
   *
   * @throws ServiceNotFoundException
   *   When the service is not defined or has the wrong type.
   */
  public static function service(string $interface, string $id = NULL): object {
    $service = \Drupal::service($id ?? $interface);
    if (!$service instanceof $interface) {
      throw new ServiceNotFoundException(\sprintf(
        'Expected %s, found %s, for service id %s.',
        $interface,
        \get_debug_type($service),
        $id ?? $interface,
      ));
    }
    return $service;
  }

}
