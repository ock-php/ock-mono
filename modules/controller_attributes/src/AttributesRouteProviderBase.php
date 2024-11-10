<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes;

use Drupal\controller_attributes\Attribute\RouteModifierInterface;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\ClassFilesIterator\NamespaceDirectory;
use Symfony\Component\Routing\Route;

abstract class AttributesRouteProviderBase {

  /**
   * @return \Symfony\Component\Routing\Route[]
   *
   * @throws \ReflectionException
   */
  public function routes(): array {
    $nsdir = NamespaceDirectory::createFromClass(static::class)
      ->subdir('Controller');
    $base_root = new Route('/');
    if ($base_root->getPath() !== '/') {
      throw new \RuntimeException('Unexpected path.');
    }
    $routes = [];
    foreach ($nsdir->withRealpathRoot() as $class) {
      $rClass = new \ReflectionClass($class);
      if ($rClass->isInterface() || $rClass->isTrait() || $rClass->isAbstract()) {
        continue;
      }
      $modifiers = AttributesUtil::getAll($rClass, RouteModifierInterface::class);
      $class_route = clone $base_root;
      foreach ($modifiers as $modifier) {
        $modifier->modifyRoute($class_route, $rClass);
      }
      foreach ($rClass->getMethods() as $rMethod) {
        if ($rMethod->isAbstract() || !$rMethod->isPublic()) {
          continue;
        }
        $modifiers = AttributesUtil::getAll($rMethod, RouteModifierInterface::class);
        if (!$modifiers) {
          continue;
        }
        $route = clone $class_route;
        $route->setDefault(
          '_controller',
          $rClass->getName() . '::' . $rMethod->getName(),
        );
        foreach ($modifiers as $modifier) {
          $modifier->modifyRoute($route, $rMethod);
        }
        $route_name = RouteNameUtil::methodGetRouteName(
          [$class, $rMethod->getName()],
        );
        if ($route->getPath() === '/') {
          throw new \RuntimeException("Route path for '$route_name' is empty.");
        }
        $routes[$route_name] = $route;
      }
    }
    return $routes;
  }

}
