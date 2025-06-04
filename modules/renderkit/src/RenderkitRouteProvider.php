<?php

declare(strict_types = 1);

namespace Drupal\renderkit;

use Drupal\controller_attributes\Attribute\RouteModifierInterface;
use Drupal\controller_attributes\RouteNameUtil;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Ock\ClassFilesIterator\NamespaceDirectory;
use Symfony\Component\Routing\Route;
use function Ock\ReflectorAwareAttributes\get_attributes;

class RenderkitRouteProvider implements ContainerInjectionInterface {

  use ContainerInjectionViaAttributesTrait;

  /**
   * @return \Symfony\Component\Routing\Route[]
   *
   * @throws \ReflectionException
   */
  public function routes(): array {
    $nsdir = NamespaceDirectory::fromClass(self::class)
      ->subdir('Controller');
    $base_root = new Route('/');
    if ($base_root->getPath() !== '/') {
      throw new \RuntimeException('Unexpected path.');
    }
    $routes = [];
    foreach ($nsdir as $class) {
      $rc = new \ReflectionClass($class);
      if ($rc->isInterface() || $rc->isTrait() || $rc->isAbstract()) {
        continue;
      }
      $modifiers = get_attributes($rc, RouteModifierInterface::class);
      $class_route = clone $base_root;
      foreach ($modifiers as $modifier) {
        $modifier->modifyRoute($class_route, $rc);
      }
      foreach ($rc->getMethods() as $rm) {
        if ($rm->isAbstract() || !$rm->isPublic()) {
          continue;
        }
        $modifiers = get_attributes($rm, RouteModifierInterface::class);
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
        $route_name = RouteNameUtil::methodGetRouteName(
          [$class, $rm->getName()]
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
