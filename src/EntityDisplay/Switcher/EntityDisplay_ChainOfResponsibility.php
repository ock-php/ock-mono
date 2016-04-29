<?php

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;

/**
 * Chain-of-responsibility entity display switcher.
 */
class EntityDisplay_ChainOfResponsibility extends EntitiesDisplayBase {

  /**
   * The displays to try.
   *
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   *   Format: $[] = $displayHandler
   */
  private $displays = array();

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $displays
   */
  function __construct(array $displays) {
    $this->displays = $displays;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array[]
   */
  function buildEntities($entityType, array $entities) {
    $builds = array_fill_keys(array_keys($entities), NULL);
    foreach ($this->displays as $display) {
      foreach ($display->buildEntities($entityType, $entities) as $delta => $build) {
        if (!empty($build)) {
          if (empty($builds[$delta])) {
            $builds[$delta] = $build;
          }
          unset($entities[$delta]);
        }
      }
      /** @noinspection DisconnectedForeachInstructionInspection */
      if (array() === $entities) {
        break;
      }
    }
    return array_filter($builds);
  }

}
