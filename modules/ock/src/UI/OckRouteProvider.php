<?php

declare(strict_types = 1);

namespace Drupal\ock\UI;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\ock\Attribute\Routing\RouteModifierInterface;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Drupal\ock\Util\StringUtil;
use Ock\ClassDiscovery\NamespaceDirectory;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Symfony\Component\Routing\Route;

class OckRouteProvider implements ContainerInjectionInterface {

  use ContainerInjectionViaAttributesTrait;

  /**
   * @return \Symfony\Component\Routing\Route[]
   *
   * @throws \ReflectionException
   */
  public function routes(): array {
    $nsdir = NamespaceDirectory::createFromClass(self::class)
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
        $route_name = StringUtil::methodGetRouteName(
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
