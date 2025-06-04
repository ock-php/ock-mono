<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes;

use Drupal\controller_attributes\Attribute\RouteModifierInterface;
use Ock\ClassFilesIterator\NamespaceDirectory;
use Symfony\Component\Routing\Route;
use function Ock\ReflectorAwareAttributes\get_attributes;

/**
 * Base class for a route provider based on discovery and attributes.
 */
abstract class AttributesRouteProviderBase {

  /**
   * @return \Symfony\Component\Routing\Route[]
   *
   * @throws \ReflectionException
   */
  public function routes(): array {
    $nsdir = NamespaceDirectory::fromClass(static::class)
      ->subdir('Controller');
    $base_root = new Route('/');
    if ($base_root->getPath() !== '/') {
      throw new \RuntimeException('Unexpected path.');
    }
    $routes = [];
    foreach ($nsdir as $class) {
      $rClass = new \ReflectionClass($class);
      if ($rClass->isInterface() || $rClass->isTrait() || $rClass->isAbstract()) {
        continue;
      }
      $modifiers = $this->getAttributes($rClass, RouteModifierInterface::class);
      $class_route = clone $base_root;
      foreach ($modifiers as $modifier) {
        $modifier->modifyRoute($class_route, $rClass);
      }
      foreach ($rClass->getMethods() as $rMethod) {
        if ($rMethod->isAbstract() || !$rMethod->isPublic()) {
          continue;
        }
        $modifiers = $this->getAttributes($rMethod, RouteModifierInterface::class);
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

  /**
   * Gets attributes from a reflector.
   *
   * @template TAttribute of object
   *
   * @param \ReflectionClass|\ReflectionMethod $reflector
   *   Class or method on which to look for attributes.
   * @param class-string<TAttribute> $name
   *   Attribute class or interface to filter by.
   *
   * @return list<TAttribute>
   */
  protected function getAttributes(\ReflectionClass|\ReflectionMethod $reflector, string $name): array {
    $attributes = $reflector->getAttributes($name, \ReflectionAttribute::IS_INSTANCEOF);
    return array_map(fn (\ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes);
  }

}
