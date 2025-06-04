<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes;

use Drupal\controller_attributes\Attribute\RouteModifierInterface;
use Symfony\Component\Routing\Route;

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
    $base_root = new Route('/');
    if ($base_root->getPath() !== '/') {
      throw new \RuntimeException('Unexpected path.');
    }
    $routes = [];
    foreach ($this->getControllerClasses() as $class) {
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
   * Gets controller classes for this module.
   *
   * @return \Iterator<class-string>
   */
  protected function getControllerClasses(): \Iterator {
    $rc = new \ReflectionClass(static::class);
    $directory = dirname($rc->getFileName()) . '/Controller';
    $namespace = $rc->getNamespaceName() . '\\Controller';
    return $this->findControllerClasses($directory, $namespace);
  }

  /**
   * Recursively
   *
   * @param string $directory
   * @param string $namespace
   *
   * @return \Iterator
   */
  protected function findControllerClasses(string $directory, string $namespace): \Iterator {
    [$dirnames, $filenames] = $this->loadDirectoryContents($directory);
    foreach ($dirnames as $name) {
      if (preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $name)) {
        yield from $this->findControllerClasses($directory . '/' . $name, $namespace . '\\' . $name);
      }
    }
    foreach ($filenames as $name) {
      if (preg_match('/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\.php$/', $name, $matches)) {
        yield $namespace . '\\' . $matches[1];
      }
    }
  }

  /**
   * Loads directory contents, and distinguishes files vs directories.
   *
   * @param string $directory
   *   Directory to search.
   * @return array{list<string>, list<string>}
   *   A list of directory names and a list of file names.
   *   Neither of them contain the parent directory.
   *   E.g. [['subdir1', 'subdir2'], ['hello.txt', 'C.php']].
   */
  protected function loadDirectoryContents(string $directory): array {
    // Use RecursiveDirectoryIterator for performance.
    // The main difference over scandir() or FilesystemIterator is that we can
    // use ->hasChildren() instead of ->isDir() or ->isFile(). This is faster,
    // because that information is already collected.
    $iterator = new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS|\FilesystemIterator::KEY_AS_FILENAME|\FilesystemIterator::CURRENT_AS_SELF);
    $subdir_names = [];
    $file_names = [];
    foreach ($iterator as $name => $iterator_self) {
      if ($iterator->hasChildren()) {
        $subdir_names[] = $name;
      }
      else {
        $file_names[] = $name;
      }
    }
    sort($subdir_names);
    sort($file_names);
    return [$subdir_names, $file_names];
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
