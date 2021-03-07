<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Plugin\Registry;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\OCUI\Plugin\Discovery\ClassToPluginsInterface;

class PluginRegistry_AnnotatedDiscovery implements PluginRegistryInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private $classFilesIA;

  /**
   * @var \Donquixote\OCUI\Plugin\Discovery\ClassToPluginsInterface
   */
  private $classToPlugins;

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   * @param \Donquixote\OCUI\Plugin\Discovery\ClassToPluginsInterface $classToPlugins
   */
  public function __construct(ClassFilesIAInterface $classFilesIA, ClassToPluginsInterface $classToPlugins) {
    $this->classFilesIA = $classFilesIA;
    $this->classToPlugins = $classToPlugins;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    $pluginsss = [];
    foreach ($this->classFilesIA as $file => $class) {
      try {
        $pluginsss[] = $this->classToPlugins->classGetPluginss($class, $file);
      }
      catch (\Exception $e) {
        // @todo Log this.
        continue;
      }
    }
    $pluginss = array_merge_recursive(...$pluginsss);
    return $pluginss;
  }

}
