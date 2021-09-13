<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\NamespaceDirectoriesIA\NamespaceDirectoriesIAInterface;
use Donquixote\Ock\Plugin\Discovery\ClassToPluginsInterface;

class PluginRegistry_ExtensionNamespaces implements PluginRegistryInterface {

  /**
   * @var \Donquixote\ClassDiscovery\NamespaceDirectoriesIA\NamespaceDirectoriesIAInterface
   */
  private $namespaceDirectories;

  /**
   * @var \Donquixote\Ock\Plugin\Discovery\ClassToPluginsInterface
   */
  private $classToPlugins;

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\NamespaceDirectoriesIA\NamespaceDirectoriesIAInterface $namespaceDirectories
   * @param \Donquixote\Ock\Plugin\Discovery\ClassToPluginsInterface $classToPlugins
   */
  public function __construct(NamespaceDirectoriesIAInterface $namespaceDirectories, ClassToPluginsInterface $classToPlugins) {
    $this->namespaceDirectories = $namespaceDirectories;
    $this->classToPlugins = $classToPlugins;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    $pluginss = [];
    foreach ($this->namespaceDirectories as $name => $namespaceDirectory) {
      $classFilesIA = ClassFilesIA::psr4FromNsdirObject($namespaceDirectory);
      $registry = new PluginRegistry_AnnotatedDiscovery($classFilesIA, $this->classToPlugins);
      foreach ($registry->getPluginss() as $type => $plugins) {
        foreach ($plugins as $id => $plugin) {
          $pluginss[$type][$name . '.' . $id] = $plugin;
        }
      }
    }
    return $pluginss;
  }

}
