<?php

namespace Donquixote\Ock\Tests;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\Ock\Exception\STABuilderException;
use Donquixote\Ock\Nursery\Nursery_FromCradle;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\ParamToLabel\ParamToLabel;
use Donquixote\Ock\Plugin\Discovery\ClassToPlugins_NativeReflection;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Donquixote\Ock\Plugin\Map\PluginMap_Registry;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;
use Donquixote\Ock\Plugin\Registry\PluginRegistry_FromClassFilesIA;
use Donquixote\Ock\Plugin\Registry\PluginRegistryInterface;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;
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
   * @return \Donquixote\Ock\Nursery\NurseryInterface
   *   The object.
   */
  protected function getFormulaToAnything(array $objects = []): NurseryInterface {
    $logger = new TestLogger();
    $objects[] = new ParamToLabel();
    $objects[] = $logger;
    $objects[] = $this->getPluginMap();
    $objects[] = new PluginGroupLabels([]);
    $param_to_value = new ParamToValue_ObjectsMatchType($objects);
    try {
      $fta = Nursery_FromCradle::create(
        $param_to_value,
        'test_cid');
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
   * @return \Donquixote\Ock\Plugin\Map\PluginMap_Registry
   */
  protected function getPluginMap(): PluginMapInterface {
    return new PluginMap_Registry($this->getPluginRegistry());
  }

  /**
   * @return \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface
   */
  protected function getPluginRegistry(): PluginRegistryInterface {
    $classFilesIA = ClassFilesIA::psr4FromClass(IntOpInterface::class, 1);
    $classToPlugins = ClassToPlugins_NativeReflection::create('ock');
    return new PluginRegistry_FromClassFilesIA(
      $classFilesIA,
      $classToPlugins);
  }

}
