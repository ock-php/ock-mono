<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\Ock\Plugin\Discovery\ClassToPluginsInterface;

class PluginRegistry_FromClassFilesIA implements PluginRegistryInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private $classFilesIA;

  /**
   * @var \Donquixote\Ock\Plugin\Discovery\ClassToPluginsInterface
   */
  private $classToPlugins;

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   * @param \Donquixote\Ock\Plugin\Discovery\ClassToPluginsInterface $classToPlugins
   */
  public function __construct(ClassFilesIAInterface $classFilesIA, ClassToPluginsInterface $classToPlugins) {
    $this->classFilesIA = $classFilesIA;
    $this->classToPlugins = $classToPlugins;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    /**
     * @var \Donquixote\Ock\Plugin\Plugin[][][] $pluginsss
     *   Format: $[][$type][$id] = $plugin.
     */
    $pluginsss = [];
    foreach ($this->classFilesIA as $file => $class) {
      $pluginsss[] = $this->classToPlugins->classGetPluginss($class, $file);
    }
    if (!$pluginsss) {
      return [];
    }
    $pluginss = array_merge_recursive(...$pluginsss);
    return $pluginss;
  }

}
