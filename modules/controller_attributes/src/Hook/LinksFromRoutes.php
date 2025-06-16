<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Menu\LocalActionManagerInterface;
use Drupal\Core\Menu\LocalTaskManagerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Routing\RouteProviderInterface;
use Symfony\Component\Routing\Route;

class LinksFromRoutes {

  public function __construct(
    protected RouteProviderInterface $provider,
    protected LocalActionManagerInterface $localActionManager,
    protected LocalTaskManagerInterface $localTaskManager,
  ) {}

  #[Hook('menu_links_discovered_alter')]
  public function menuLinksDiscoveredAlter(&$links): void {
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
      }
      $links['routelink:' . $k] = $link;
    }
  }

  #[Hook('menu_local_actions_alter')]
  public function menuLocalActionsAlter(array &$local_actions): void {
    foreach ($this->provider->getAllRoutes() as $k => $route) {
      if (!is_array($link = $route->getOption('_action_link'))) {
        continue;
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
        continue;
      }
      if ($this->localActionManager instanceof DefaultPluginManager) {
        $this->localActionManager->processDefinition($link, 'routelink:' . $k);
      }
      $local_actions['routelink:' . $k] = $link;
    }
  }

  /**
   * Implements hook_local_tasks_alter().
   */
  #[Hook('local_tasks_alter')]
  public function localTasksAlter(array &$local_tasks): void {
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
      if ($this->localTaskManager instanceof DefaultPluginManager) {
        $this->localTaskManager->processDefinition($link, 'routelink:' . $k);
      }
      $local_tasks['routelink:' . $k] = $link;
    }
  }

  /**
   * Attempts to find a parent route for a given route, based on the path.
   *
   * @param \Symfony\Component\Routing\Route $route
   *   The route for which to find a parent.
   *
   * @return string|null
   *   A parent route name, or NULL if none found.
   */
  protected function routeGetParentName(Route $route): ?string {

    $path = $route->getPath();
    $parent_path = \dirname($path);
    if ($parent_path === '/' || $parent_path === '') {
      return NULL;
    }

    $candidate_routes = $this->provider->getRoutesByPattern($parent_path);
    foreach ($candidate_routes as $candidate_name => $candidate_route) {
      if ($candidate_route->getPath() === $parent_path) {
        return $candidate_name;
      }
    }

    return NULL;
  }

}
