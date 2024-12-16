<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Controller;

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
