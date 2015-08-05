<?php

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\EntityDisplayMultipleBase;

/**
 * Chain-of-responsibility entity display switcher.
 */
class CorEntityDisplaySwitcher extends EntityDisplayMultipleBase {

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
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   */
  function buildMultiple($entity_type, array $entities) {
    $builds = array_fill_keys(array_keys($entities), NULL);
    foreach ($this->displays as $display) {
      foreach ($display->buildMultiple($entity_type, $entities) as $delta => $build) {
        if (!empty($build)) {
          if (empty($builds[$delta])) {
            $builds[$delta] = $build;
          }
          unset($entities[$delta]);
        }
      }
    }
    return array_filter($builds);
  }

}
