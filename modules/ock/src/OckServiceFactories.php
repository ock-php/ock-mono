<?php

declare(strict_types=1);

namespace Drupal\ock;

use Ock\Adaptism\AdapterDefinitionList\AdapterDefinitionList_Discovery;
use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIA_Multiple;
use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Ock\ClassDiscovery\NamespaceDirectory;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;
use Ock\Ock\OckPackage;
use Ock\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Ock\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Ock\Ock\Plugin\Registry\PluginRegistry_Discovery;
use Ock\Ock\Text\Text;
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
   * @return \Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
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
   * @return \Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
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
