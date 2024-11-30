<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Ock\Testing\Diff\DifferInterface;
use Ock\Testing\Diff\ExportedArrayDiffer;
use Ock\Testing\Exporter\Exporter_ToYamlArray;
use Ock\Testing\Exporter\ExporterInterface;
use Ock\Testing\Snapshotter\SnapshotterInterface;

abstract class AdvancedSnapshotterBase implements SnapshotterInterface, DifferInterface {

  /**
   * {@inheritdoc}
   */
  public function takeSnapshot(): array {
    $items = $this->getItems();
    if (!$items) {
      return [];
    }
    $exporter = $this->createExporter();
    $items = $exporter->export($items, 15);
    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function compare(array $before, array $after): array {
    $diff = $this->createDiffer()->compare($before, $after);
    $exporter = $this->createExporter();
    foreach ($diff as $op => &$items) {
      if ($op === '!=') {
        continue;
      }
      foreach ($items as $id => &$item) {
        $default_item = $this->getDefaultItemForId($id);
        $default_item = $exporter->export($default_item);
        foreach ($default_item as $key => $default_value) {
          if (($item[$key] ?? NULL) === $default_value) {
            unset($item[$key]);
          }
        }
      }
    }
    return $diff;
  }

  /**
   * @return array
   */
  abstract protected function getItems(): array;

  /**
   * @param int|string $id
   *   Key for the item.
   *
   * @return array|object
   */
  protected function getDefaultItemForId(int|string $id): array|object {
    return [];
  }

  /**
   * Creates an exporter to process asserted values.
   */
  protected function createExporter(): ExporterInterface {
    return new Exporter_ToYamlArray();
  }

  protected function createDiffer(): DifferInterface {
    return new ExportedArrayDiffer();
  }

}
