<?php

namespace Donquixote\OCUI\Tests;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormula_PluginMap;
use Donquixote\OCUI\Exception\STABuilderException;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnything_FromPartial;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\ParamToLabel\ParamToLabel;
use Donquixote\OCUI\Plugin\Discovery\ClassToPlugins_NativeReflection;
use Donquixote\OCUI\Plugin\Map\PluginMap_Registry;
use Donquixote\OCUI\Plugin\Map\PluginMapInterface;
use Donquixote\OCUI\Plugin\Registry\PluginRegistry_AnnotatedDiscovery;
use Donquixote\OCUI\Plugin\Registry\PluginRegistryInterface;
use Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValue_ObjectsMatchType;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class FormulaTestBase extends TestCase {

  /**
   * Gets a FormulaToAnything object.
   *
   * @param object[] $objects
   *   Objects to pass to the constructors.
   *
   * @return \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface
   *   The object.
   */
  protected function getFormulaToAnything(array $objects = []): FormulaToAnythingInterface {
    $logger = new TestLogger();
    $objects[] = new ParamToLabel();
    $objects[] = $logger;
    $objects[] = new TypeToFormula_PluginMap($this->getPluginMap());
    $param_to_value = new ParamToValue_ObjectsMatchType($objects);
    try {
      $fta = FormulaToAnything_FromPartial::create(
        $param_to_value);
    }
    catch (STABuilderException $e) {
      static::fail(sprintf(
        'Exception creating the FTA: %s.',
        $e->getMessage()));
    }
    foreach ($logger->records as $record) {
      self::fail(sprintf(
        'Message when creating FTA: %s.',
        $record['message']));
    }
    return $fta;
  }

  /**
   * @return \Donquixote\OCUI\Plugin\Map\PluginMap_Registry
   */
  protected function getPluginMap(): PluginMapInterface {
    return new PluginMap_Registry($this->getPluginRegistry());
  }

  /**
   * @return \Donquixote\OCUI\Plugin\Registry\PluginRegistryInterface
   */
  protected function getPluginRegistry(): PluginRegistryInterface {
    $classFilesIA = ClassFilesIA::psr4FromClass(IntOpInterface::class, 1);
    $classToPlugins = ClassToPlugins_NativeReflection::create('ocui');
    return new PluginRegistry_AnnotatedDiscovery(
      $classFilesIA,
      $classToPlugins);
  }

}
