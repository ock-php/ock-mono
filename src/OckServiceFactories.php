<?php

declare(strict_types=1);

namespace Drupal\ock;

use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionList_Discovery;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Multiple;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\NamespaceDirectory;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\Ock\OckPackage;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Donquixote\Ock\Plugin\Registry\PluginRegistry_Discovery;
use Donquixote\Ock\Text\Text;
use Drupal\Core\Extension\ModuleExtensionList;

/**
 * Some service factories that don't have their own class.
 *
 * @see \Drupal\ock\OckServiceProvider
 */
class OckServiceFactories {

  /**
   * Namespaces where plugins are defined.
   *
   * @param \Drupal\Core\Extension\ModuleExtensionList $modules
   * @param \Traversable $namespaces
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  #[Service(serviceIdSuffix: PluginRegistry_Discovery::class)]
  public static function pluginClassFiles(
    #[GetService('extension.list.module')]
    ModuleExtensionList $modules,
    #[GetService('container.namespaces')]
    \Traversable $namespaces,
  ): ClassFilesIAInterface {
    // Unpack the traversable.
    $namespace_dirss = [...$namespaces];
    $classFilesIAs = [];
    foreach ($modules->getList() as $module => $obj) {
      $namespace = "Drupal\\$module";
      /** @var list<string>|string $dirs */
      $dirs = $namespace_dirss[$namespace]
        ?? $namespace_dirss[$namespace . '\\']
        ?? [];
      if (!$dirs) {
        continue;
      }
      if (!\is_file($obj->getPath() . '/' . $module . '.ock.yml')) {
        continue;
      }
      $path = $obj->getPath() . '/src';
      if ($path !== $dirs) {
        if (!\is_array($dirs)) {
          continue;
        }
        if (!\in_array($path, $dirs)) {
          continue;
        }
      }
      $classFilesIAs[] = NamespaceDirectory::create($path, $namespace);
    }
    // Add example plugins.
    # $classFilesIAs[] = ClassFilesIA::psr4FromClass(IntOpInterface::class, 1);
    return new ClassFilesIA_Multiple($classFilesIAs);
  }

  #[Service]
  public static function pluginGroupLabels(
    #[GetService('extension.list.module')] ModuleExtensionList $modules,
  ): PluginGroupLabelsInterface {
    $labels = [];
    foreach ($modules->getList() as $module => $info) {
      $labels[$module] = Text::s($info->getName());
    }
    return new PluginGroupLabels($labels);
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   *
   * @throws \ReflectionException
   */
  #[Service(serviceIdSuffix: AdapterDefinitionList_Discovery::class)]
  public static function getAdapterClassFilesIA(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClasses([
      OckPackage::class,
      self::class,
    ]);
  }

}
