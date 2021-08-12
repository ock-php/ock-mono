<?php

declare(strict_types=1);

namespace Drupal\cu;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Multiple;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\NamespaceDirectory;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormula_PluginMap;
use Donquixote\OCUI\Discovery\STADiscovery_X;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnything_FromPartial;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartial_SmartChain;
use Donquixote\OCUI\ParamToLabel\ParamToLabel;
use Donquixote\OCUI\Plugin\Map\PluginMapInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;
use Donquixote\OCUI\Util\LocalPackageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValue_ObjectsMatchType;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CuServices {

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param \Psr\Log\LoggerInterface $logger
   * @param \Donquixote\OCUI\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   *
   * @return \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface
   */
  public static function formulaToAnything(ContainerInterface $container, LoggerInterface $logger, PluginMapInterface $pluginMap, TranslatorInterface $translator): FormulaToAnythingInterface {
    $objects[] = new ParamToLabel();
    $objects[] = $logger;
    $objects[] = new TypeToFormula_PluginMap($pluginMap);
    $objects[] = $translator;
    $param_to_value = new ParamToValue_ObjectsMatchType($objects);
    $class_files_ia = self::getClassFilesIA();
    $partials = STADiscovery_X::create($param_to_value)
      ->classFilesIAGetPartials($class_files_ia);
    $fta_partial = new FormulaToAnythingPartial_SmartChain($partials);

    return new FormulaToAnything_FromPartial($fta_partial);
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
