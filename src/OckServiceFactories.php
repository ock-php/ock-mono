<?php

declare(strict_types=1);

namespace Drupal\ock;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Multiple;
use Donquixote\ClassDiscovery\NamespaceDirectory;
use Donquixote\Ock\FormulaAdapter;
use Donquixote\Ock\ParamToLabel\ParamToLabel;
use Donquixote\Ock\ParamToLabel\ParamToLabelInterface;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Donquixote\Ock\Plugin\Map\PluginMap_Registry;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;
use Donquixote\Ock\Plugin\Registry\PluginRegistry_Discovery;
use Donquixote\Ock\Plugin\Registry\PluginRegistryInterface;
use Donquixote\Ock\Text\Text;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\ock\Attribute\DI\RegisterService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Static factories for services to be registered automatically.
 *
 * @see \Drupal\ock\OckServiceProvider
 */
class OckServiceFactories {

  /**
   * Registry of plugins provided by Drupal modules.
   *
   * @param \Drupal\Core\Extension\ModuleExtensionList $modules
   * @param \Traversable<string, list<string>|string> $namespaces
   *   Format: $[$namespace][] = $dir.
   *
   * @return \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface
   */
  #[RegisterService]
  public static function pluginRegistry(
    #[DrupalService('extension.list.module')]
    ModuleExtensionList $modules,
    #[DrupalService('container.namespaces')]
    \Traversable $namespaces
  ): PluginRegistryInterface {
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
    $classFilesIA = new ClassFilesIA_Multiple($classFilesIAs);
    return PluginRegistry_Discovery::fromClassFilesIA($classFilesIA);
  }

  /**
   * @param \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface $plugin_registry
   *
   * @return \Donquixote\Ock\Plugin\Map\PluginMapInterface
   */
  #[RegisterService]
  public static function pluginMap(PluginRegistryInterface $plugin_registry): PluginMapInterface {
    return new PluginMap_Registry($plugin_registry);
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface
   * @throws \ReflectionException
   */
  #[RegisterService]
  public static function adapter(
    ContainerInterface $container,
  ): UniversalAdapterInterface {
    return UniversalAdapter::fromClassFilesIA(
      new ClassFilesIA_Multiple([
        // Discover adapters in `\Donquixote\Ock\`.
        ClassFilesIA::psr4FromClass(FormulaAdapter::class),
        // Discover adapters in `\Drupal\ock\`.
        ClassFilesIA::psr4FromClass(self::class),
        // @todo Provide a mechanism for modules to add more adapters.
      ]),
      $container,
    );
  }

  #[RegisterService]
  public static function pluginGroupLabels(
    #[DrupalService('extension.list.module')] ModuleExtensionList $modules,
  ): PluginGroupLabelsInterface {
    $labels = [];
    foreach ($modules->getList() as $module => $info) {
      $labels[$module] = Text::s($info->getName());
    }
    return new PluginGroupLabels($labels);
  }

  /**
   * @return \Donquixote\Ock\ParamToLabel\ParamToLabelInterface
   */
  #[RegisterService]
  public static function paramToLabel(): ParamToLabelInterface {
    return new ParamToLabel();
  }

}
