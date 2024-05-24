<?php

declare(strict_types = 1);

namespace Drupal\renderkit\UI;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\ock\Attribute\Routing\RouteModifierInterface;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Drupal\ock\Util\StringUtil;
use Ock\ClassDiscovery\NamespaceDirectory;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Symfony\Component\Routing\Route;

class RenderkitRouteProvider implements ContainerInjectionInterface {

  use ContainerInjectionViaAttributesTrait;

  /**
   * @return \Symfony\Component\Routing\Route[]
   *
   * @throws \ReflectionException
   */
  public function routes(): array {
    $nsdir = NamespaceDirectory::createFromClass(self::class)
      ->parent()
      ->subdir('Controller');
    $base_root = new Route('/');
    if ($base_root->getPath() !== '/') {
      throw new \RuntimeException('Unexpected path.');
    }
    $routes = [];
    foreach ($nsdir->withRealpathRoot() as $class) {
      $rc = new \ReflectionClass($class);
      if ($rc->isInterface() || $rc->isTrait() || $rc->isAbstract()) {
        continue;
      }
      $modifiers = AttributesUtil::getAll($rc, RouteModifierInterface::class);
      $class_route = clone $base_root;
      foreach ($modifiers as $modifier) {
        $modifier->modifyRoute($class_route, $rc);
      }
      foreach ($rc->getMethods() as $rm) {
        if ($rm->isAbstract() || !$rm->isPublic()) {
          continue;
        }
        $modifiers = AttributesUtil::getAll($rm, RouteModifierInterface::class);
        if (!$modifiers) {
          continue;
        }
        $route = clone $class_route;
        $route->setDefault(
          '_controller',
          $rc->getName() . '::' . $rm->getName());
        foreach ($modifiers as $modifier) {
          $modifier->modifyRoute($route, $rm);
        }
        $route_name = StringUtil::methodGetRouteName(
          [$class, $rm->getName()]);
        if ($route->getPath() === '/') {
          throw new \RuntimeException("Route path for '$route_name' is empty.");
        }
        $routes[$route_name] = $route;
      }
    }
    return $routes;
  }

}
