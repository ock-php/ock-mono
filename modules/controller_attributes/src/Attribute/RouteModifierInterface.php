<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

use Symfony\Component\Routing\Route;

interface RouteModifierInterface {

  /**
   * @param \Symfony\Component\Routing\Route $route
   *   Route object to be modified by this annotation.
   * @param \ReflectionClass|\ReflectionMethod $reflector
   *   Class or method where the attribute was attached to.
   */
  public function modifyRoute(Route $route, \ReflectionClass|\ReflectionMethod $reflector): void;

}
