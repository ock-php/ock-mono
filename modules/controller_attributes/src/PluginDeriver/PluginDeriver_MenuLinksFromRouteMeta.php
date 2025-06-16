<?php
declare(strict_types=1);

namespace Drupal\controller_attributes\PluginDeriver;

final class PluginDeriver_MenuLinksFromRouteMeta extends LinkPluginDeriverBase {

  /**
   * @return array[]
   */
  protected function buildDerivativeDefinitions(): array {

    $definitions = [];
    foreach ($this->provider->getAllRoutes() as $k => $route) {

      if (NULL === $link = $route->getOption('_menu_link')) {
        continue;
      }

      if (!\is_array($link)) {
        // @todo Skip invalid values like this.
        $link = [];
      }

      $link += [
        'title' => $route->getDefault('_title'),
        'route_name' => $k,
      ];

      if (!isset($link['parent'])) {
        $link['parent'] = $this->routeGetParentName($route);
      }

      $definitions[$k] = $link;
    }

    foreach ($definitions as $k => $link) {
      if (isset($definitions[$link['parent']])) {
        $link['parent'] = 'routelink:' . $link['parent'];
        $definitions[$k] = $link;
      }
    }

    return $definitions;
  }

}
