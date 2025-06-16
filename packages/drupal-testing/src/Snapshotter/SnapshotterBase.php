<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Ock\Testing\Exporter\Exporter_ToYamlArray;
use Ock\Testing\Exporter\ExporterInterface;
use Ock\Testing\Snapshotter\SnapshotterInterface;

abstract class SnapshotterBase implements SnapshotterInterface {

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
    $raw_default = $this->getDefaultItem();
    if (!$raw_default) {
      return $items;
    }
    $default = $exporter->export($raw_default, 15);
    $items = array_map(
      function ($item) use ($exporter, $default) {
        foreach ($default as $key => $default_value) {
          if (!array_key_exists($key, $item)) {
            $item[$key] = '(missing)';
          }
          elseif ($item[$key] === $default_value) {
            unset($item[$key]);
          }
        }
        return $item;
      },
      $items,
    );
    return $items;
  }

  /**
   * @return array
   */
  abstract protected function getItems(): array;

  /**
   * @return array|object
   */
  protected function getDefaultItem(): array|object {
    return [];
  }

  /**
   * Creates an exporter to process asserted values.
   */
  protected function createExporter(): ExporterInterface {
    return new Exporter_ToYamlArray();
  }

}
