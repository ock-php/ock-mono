<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting;

use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleInstallerInterface;
use Ock\Testing\Diff\ExportedArrayDiffer;
use Ock\Testing\FileAsRecordedTrait;
use Ock\Testing\IsRecordingTrait;
use Ock\Testing\RecordingsPathTrait;
use Ock\Testing\Snapshotter\DiffingMultiSnapshotter;
use PHPUnit\Framework\Attributes\DataProvider;

trait ModuleSnapshotTestTrait {

  use IsRecordingTrait;
  use RecordingsPathTrait;
  use FileAsRecordedTrait;

  /**
   * Tests snapshots for this module.
   */
  #[DataProvider('provideModuleNames')]
  public function testSnapshotDiff(string $module): void {
    $diffs = $this->createDiffs($module);
    $reports = $this->createReportsFromDiffs($module, $diffs);
    $this->assertReportsAsRecorded($module, $reports);
  }

  /**
   * Creates diffs to compare the situation without and with the module.
   *
   * @param string $module
   *   Module to enable as part of the experiment.
   *
   * @return array<string, array>
   *   Diffs for the different snapshotters.
   */
  protected function createDiffs(string $module): array {
    $snapshotters = $this->getSnapshotters();
    $this->installModuleDependencies($module);
    $multi = new DiffingMultiSnapshotter($snapshotters, new ExportedArrayDiffer());
    $before = $multi->takeSnapshot();
    $this->installModule($module);
    $after = $multi->takeSnapshot();
    return $multi->compare($before, $after);
  }

  /**
   * Adds additional information to diffs generated earlier.
   *
   * @param string $module
   * @param array<string, array> $diffs
   *
   * @return array<string, array|null>
   */
  protected function createReportsFromDiffs(string $module, array $diffs): array {
    $reports = [];
    foreach ($diffs as $key => $diff) {
      if (!$diff) {
        $reports[$key] = NULL;
      }
      else {
        $info = [
          'module' => $module,
          'type' => $key,
        ];
        $reports[$key] = $info + $diff;
      }
    }
    $reports['summary'] = [
      'module' => $module,
      'snapshots' => array_map(
        fn (array $diff) => $diff ? '!=' : '==',
        $diffs,
      ),
    ];
    return $reports;
  }

  /**
   * @param string $prefix
   * @param array<string, array|null> $reports
   */
  protected function assertReportsAsRecorded(string $prefix, array $reports): void {
    $base_path = $this->getClassRecordingsPath();
    foreach ($reports as $key => $report) {
      $yaml = ($report === NULL) ? NULL : Yaml::encode($report);
      $file = $base_path . '/' . $prefix . '.' . $key . '.yml';
      $this->assertFileAsRecorded($file, $yaml);
    }
  }

  /**
   * Data provider.
   *
   * @return \Iterator<string, array{string}>
   *   Parameter combos, each of which passes a module name to the test method.
   */
  public static function provideModuleNames(): \Iterator {
    foreach (static::getTestedModuleNames() as $module) {
      yield $module => [$module];
    }
  }

  /**
   * @return list<string>
   */
  protected static function getTestedModuleNames(): array {
    if (!preg_match('@^Drupal\\\\Tests\\\\(\w+)\\\\@', static::class, $matches)) {
      throw new \RuntimeException(sprintf('Class name %s does not imply a module name.', static::class));
    }
    return [$matches[1]];
  }

  /**
   * Installs the dependencies of a module, but not the module itself.
   *
   * @param string $module
   *   Module the dependencies of which to install.
   */
  protected function installModuleDependencies(string $module): void {
    $info = DrupalTesting::service(ModuleExtensionList::class)->get($module);
    assert(property_exists($info, 'requires'));
    $dependencies = array_keys($info->requires);
    DrupalTesting::service(ModuleInstallerInterface::class)->install(['system', ...$dependencies]);
  }

  /**
   * Installs a module.
   *
   * @param string $module
   *   Module the dependencies of which to install.
   */
  protected function installModule(string $module): void {
    DrupalTesting::service(ModuleInstallerInterface::class)->install([$module]);
  }

  /**
   * Gets the snapshot plugins to use for this test.
   *
   * @return array<string, \Ock\Testing\Snapshotter\SnapshotterInterface>
   */
  abstract protected function getSnapshotters(): array;

}
