<?php

declare(strict_types=1);

namespace Drupal\ock\NamespaceDirectoriesIA;

use Donquixote\ClassDiscovery\NamespaceDirectoriesIA\NamespaceDirectoriesIAInterface;
use Donquixote\ClassDiscovery\NamespaceDirectory;
use Drupal\Core\Extension\ModuleExtensionList;

class NamespaceDirectoriesIA_ModuleNamespaces implements NamespaceDirectoriesIAInterface {

  /**
   * @var \Drupal\Core\Extension\ModuleExtensionList
   */
  private $modules;

  /**
   * @var \Traversable
   */
  private $namespaces;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Extension\ModuleExtensionList $modules
   *   Module extension list.
   * @param \Traversable $namespaces
   *   Module namespaces.
   *   Format: $[$namespace] = $dir|$dirs
   */
  public function __construct(ModuleExtensionList $modules, \Traversable $namespaces) {
    $this->modules = $modules;
    $this->namespaces = $namespaces;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    $module_names_by_namespace = [];
    foreach ($this->modules->getList() as $module => $obj) {
      $module_names_by_namespace["Drupal\\$module"] = $module;
    }
    foreach ($this->namespaces as $namespace => $dirs) {
      $module = $module_names_by_namespace[$namespace] ?? NULL;
      if ($module === NULL) {
        continue;
      }
      foreach ((array) $dirs as $dir) {
        yield $module => NamespaceDirectory::create($dir, $namespace);
      }
    }
  }

}
