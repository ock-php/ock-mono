<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes;

use Drupal\controller_attributes\Attribute\RouteModifierInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;

/**
 * Base class for a route provider based on discovery and attributes.
 */
class ControllerAttributesRouteProvider implements ContainerInjectionInterface {

  public function __construct(
    protected readonly ModuleHandlerInterface $moduleHandler,
    protected readonly ModuleExtensionList $moduleList,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get(ModuleHandlerInterface::class),
      $container->get(ModuleExtensionList::class),
    );
  }

  /**
   * Collects routes from all participating modules.
   *
   * @return array<string, \Symfony\Component\Routing\Route>
   *   Routes by route name.
   */
  public function routes(): array {
    $routes = [];
    $list = $this->moduleList->getList();
    foreach ($this->moduleHandler->getModuleList() as $module => $module_info) {
      assert(isset($list[$module]), $module);
      // Modules can opt-in by adding the dependency.
      // An indirect dependency does not work for this.
      if (!array_intersect([
        'controller_attributes:controller_attributes',
        'controller_attributes',
      ], $list[$module]->info['dependencies'] ?? [])) {
        continue;
      }
      $directory = $module_info->getPath() . '/src/Controller';
      $namespace = 'Drupal\\' . $module . '\\Controller';
      foreach ($this->findControllerClasses($directory, $namespace) as $class) {
        $routes = array_replace($routes, $this->getRoutesForClass($class));
      }
    }
    return $routes;
  }

  /**
   * Collects routes for a controller class.
   *
   * @param class-string $class
   *   Controller class.
   *
   * @return array<string, \Symfony\Component\Routing\Route>
   *   Routes by route name.
   */
  protected function getRoutesForClass(string $class): array {
    $base_root = new Route('/');
    if ($base_root->getPath() !== '/') {
      throw new \RuntimeException('Unexpected path.');
    }
    $routes = [];
    $rClass = new \ReflectionClass($class);
    if ($rClass->isInterface() || $rClass->isTrait() || $rClass->isAbstract() || $rClass->isEnum()) {
      return [];
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
    return $routes;
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
