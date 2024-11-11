<?php
declare(strict_types=1);

namespace Drupal\controller_attributes\PluginDeriver;

final class PluginDeriver_ActionLinksFromRouteMeta extends LinkPluginDeriverBase {

  /**
   * {@inheritdoc}
   */
  protected function buildDerivativeDefinitions(): array {

    $definitions = [];
    foreach ($this->provider->getAllRoutes() as $k => $route) {

      if (NULL === $link = $route->getOption('_action_link')) {
        continue;
      }

      if (\is_string($link)) {
        $link = ['title' => $link];
      }
      elseif (!\is_array($link)) {
        $link = [];
      }

      $link += [
        'title' => $route->getDefault('_title'),
        'route_name' => $k,
      ];

      if (!isset($link['appears_on'])) {
        if (NULL === $parentRouteName = $this->routeGetParentName($route)) {
          continue;
        }

        $link['appears_on'] = [$parentRouteName];
      }
      elseif (!\is_array($link['appears_on'])) {
        $link['appears_on'] = [$link['appears_on']];
      }

      $definitions[$k] = $link;
    }

    return $definitions;
  }

}
