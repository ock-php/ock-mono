<?php

declare(strict_types=1);

namespace Drupal\ock;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Multiple;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\NamespaceDirectory;
use Donquixote\Ock\Discovery\STADiscovery_X;
use Donquixote\Ock\Incarnator\Incarnator_SmartChain;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\ParamToLabel\ParamToLabel;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;
use Donquixote\Ock\Translator\TranslatorInterface;
use Donquixote\Ock\Util\LocalPackageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValue_ObjectsMatchType;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OckServices {

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param \Psr\Log\LoggerInterface $logger
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   *
   * @return \Donquixote\Ock\Incarnator\IncarnatorInterface
   */
  public static function incarnator(ContainerInterface $container, LoggerInterface $logger, PluginMapInterface $pluginMap, TranslatorInterface $translator): IncarnatorInterface {
    $objects[] = new ParamToLabel();
    $objects[] = $logger;
    $objects[] = $pluginMap;
    $objects[] = $translator;
    // @todo Fill this with Drupal module labels.
    $objects[] = new PluginGroupLabels([]);
    $param_to_value = new ParamToValue_ObjectsMatchType($objects);
    $class_files_ia = self::getClassFilesIA();
    $partials = STADiscovery_X::create($param_to_value)
      ->classFilesIAGetPartials($class_files_ia);
    return new Incarnator_SmartChain($partials);
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private static function getClassFilesIA(): ClassFilesIAInterface {
    return new ClassFilesIA_Multiple([
      LocalPackageUtil::getClassFilesIA(),
      ClassFilesIA_NamespaceDirectoryPsr4::createFromNsdirObject(
        NamespaceDirectory::create(__DIR__, __NAMESPACE__)
          ->subdir('Formator')),
    ]);
  }

}
