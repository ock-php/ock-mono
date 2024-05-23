<?php

declare(strict_types = 1);

namespace Drupal\ock\UI;

use Symfony\Component\Routing\Route;

class RouteParentFinder {

  /**
   * @param \Symfony\Component\Routing\Route $route
   *
   * @return string|null
   */
  protected function routeGetParentName(Route $route): ?string {

    $path = $route->getPath();
    $parent_path = \dirname($path);

    try {
      $parent_route = $this->router->match($parent_path);
    }
    catch (\Exception $e) {
      // @todo Don't just silently ignore this.
      unset($e);
      return NULL;
    }

    if (empty($parent_route['_route'])) {
      return NULL;
    }

    return $parent_route['_route'];
  }

}
