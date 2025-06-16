<?php
declare(strict_types=1);

namespace Drupal\controller_attributes\PluginDeriver;

final class PluginDeriver_TaskLinksFromRouteMeta extends LinkPluginDeriverBase {

  /**
   * @return array[]
   */
  protected function buildDerivativeDefinitions(): array {

    $definitions = [];
    foreach ($this->provider->getAllRoutes() as $k => $route) {

      if (is_array($link = $route->getOption('_task_link'))) {
        $is_default_task = FALSE;
      }
      elseif (is_array($link = $route->getOption('_task_link_default'))) {
        $is_default_task = TRUE;
      }
      else {
        continue;
      }

      if (!$is_default_task) {
        $is_default_task = !empty($link['is_default_task']);
      }

      $link += [
        'title' => $route->getDefault('_title'),
        'route_name' => $k,
      ];

      if (!isset($link['base_route'])) {
        if ($is_default_task) {
          $link['base_route'] = $k;
        }
        else {
          $link['base_route'] = $this->routeGetParentName($route);
        }
      }

      $definitions[$k] = $link;
    }

    return $definitions;
  }

}
