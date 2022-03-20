<?php

namespace Donquixote\Ock\Tests;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\Ock\Exception\STABuilderException;
use Donquixote\Ock\Incarnator\Incarnator_FromPartial;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\ParamToLabel\ParamToLabel;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Donquixote\Ock\Plugin\Map\PluginMap_Registry;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;
use Donquixote\Ock\Plugin\Registry\PluginRegistry_Discovery;
use Donquixote\Ock\Plugin\Registry\PluginRegistryInterface;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\Ock\Translator\Translator_Passthru;
use Donquixote\ReflectionKit\ParamToValue\ParamToValue_ObjectsMatchType;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class FormulaTestBase extends TestCase {

  /**
   * Gets an Incarnator* object.
   *
   * @param object[] $objects
   *   Objects to pass to the constructors.
   *
   * @return \Donquixote\Ock\Incarnator\IncarnatorInterface
   *   The object.
   */
  protected function getIncarnator(array $objects = []): IncarnatorInterface {
    # $logger = new TestLogger();
    $objects[] = new ParamToLabel();
    # $objects[] = $logger;
    $objects[] = $this->getPluginMap();
    $objects[] = new PluginGroupLabels([]);
    $objects[] = new Translator_Passthru();
    $param_to_value = new ParamToValue_ObjectsMatchType($objects);
    try {
      $incarnator = Incarnator_FromPartial::create(
        $param_to_value,
        'test_cid');
    }
    catch (STABuilderException $e) {
      static::fail(sprintf(
        'Exception creating the FTA: %s.',
        $e->getMessage()));
    }
    if (false) foreach ($logger->records as $record) {
      self::fail(sprintf(
        'Message when creating FTA: %s.',
        $record['message']));
    }
    return $incarnator;
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
    return PluginRegistry_Discovery::fromClassFilesIA($classFilesIA);
  }

  /**
   * Data provider.
   *
   * @return \Iterator
   *   Parameter combos.
   */
  public function providerTestFormula(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures/formula';
    $candidates = scandir($dir);
    // Prevent duplicates, but detect orphan files.
    $comboss_map = [];
    foreach ($candidates as $candidate) {
      if (preg_match('@^(\w+)\.(\w+)\.\w+$@', $candidate, $m)) {
        [, $base, $case] = $m;
        $comboss_map[$base][$case] = TRUE;
      }
    }
    foreach ($comboss_map as $base => $cases_map) {
      /** @psalm-suppress RedundantCast */
      $base = (string) $base;
      foreach ($cases_map as $case => $_) {
        /* @psalm-suppress RedundantCast */
        yield [$base, (string) $case];
      }
    }
  }

  /**
   * Data provider.
   *
   * @return \Iterator|array[]
   *   Argument combos.
   */
  public function providerTestIface(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures/iface';
    foreach (scandir($dir) as $dir_candidate) {
      if ($dir_candidate === '.' || $dir_candidate === '..') {
        continue;
      }
      // Prevent duplicates, but detect orphan files.
      $names_map = [];
      foreach (scandir($dir . '/' . $dir_candidate) as $file_candidate) {
        if (preg_match('@^(\w+)\.\w+$@', $file_candidate, $m)) {
          $names_map[$m[1]] = TRUE;
        }
      }
      foreach ($names_map as $name => $_) {
        yield [$dir_candidate, $name];
      }
    }
  }

}
