<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting;

class DrupalTesting {

  /**
   * Gets a service by class/interface and id.
   *
   * @template T of object
   *
   * @param class-string<T> $class
   *   Class or interface.
   * @param string|null $id
   *   Service id, or NULL to use the type as id.
   *
   * @return object&T
   *   A service that is an instance of $class.
   */
  public static function service(string $class, ?string $id = NULL): object {
    $service = \Drupal::getContainer()->get($id ?? $class);
    if (!$service instanceof $class) {
      if ($id === NULL) {
        throw new \RuntimeException(sprintf(
          "Expected '%s' object, found '%s' object, for the service with that class/interface as id.",
          $class,
          get_class($service),
        ));
      }
      else {
        throw new \RuntimeException(sprintf(
          "Expected '%s' object, found '%s' object, for service '%s'.",
          $class,
          get_class($service),
          $id,
        ));
      }
    }
    return $service;
  }

}
