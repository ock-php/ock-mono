<?php

namespace Drupal\renderkit\EntityDisplay;

/**
 * Can renders each entity in an array with a different display handler.
 */
class DeltaDisplayMap implements EntityDisplayInterface {

  /**
   * @var EntityDisplayInterface
   */
  private $fallbackDisplay;

  /**
   * @var EntityDisplayInterface[]
   */
  private $displaysByKey = array();

  /**
   * @var bool[][]
   *   Format: $[$displayKey][$delta] = TRUE
   */
  private $deltasByKey = array();

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $fallbackDisplay
   */
  function __construct(EntityDisplayInterface $fallbackDisplay) {
    $this->fallbackDisplay = $fallbackDisplay;
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $display
   * @param array $deltas
   *
   * @return string
   */
  function setDisplayForDeltas(EntityDisplayInterface $display, array $deltas) {
    $displayKey = uniqid();
    $this->displaysByKey[$displayKey] = $display;
    $this->deltasByKey[$displayKey] = array_fill_keys($deltas, TRUE);
    return $displayKey;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildMultiple($entity_type, array $entities) {
    $deltas = array_keys($entities);
    // Preset the order.
    $builds = array_fill_keys($deltas, NULL);
    foreach ($this->deltasByKey as $displayKey => $display_deltas) {
      $display = $this->displaysByKey[$displayKey];
      $display_entities = array();
      foreach ($display_deltas as $delta => $cTrue) {
        if (!isset($entities[$delta])) {
          continue;
        }
        $display_entities[$delta] = $entities[$delta];
        unset($entities[$delta]);
      }
      foreach ($display->buildMultiple($entity_type, $display_entities) as $delta => $build) {
        $builds[$delta] = $build;
      }
    }
    foreach ($this->fallbackDisplay->buildMultiple($entity_type, $entities) as $delta => $build) {
      $builds[$delta] = $build;
    }
    return $builds;
  }
}
