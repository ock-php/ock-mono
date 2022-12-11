<?php

namespace Drupal\ock\UI\Controller;

/**
 * Interface that annotated controllers can implement to provide custom route names.
 */
interface ControllerRouteNameInterface {

  /**
   * @param \ReflectionMethod $method
   *
   * @return string|null
   */
  public static function methodGetRouteName(\ReflectionMethod $method): ?string;

}
