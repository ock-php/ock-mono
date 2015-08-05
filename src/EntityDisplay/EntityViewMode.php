<?php

namespace Drupal\renderkit\EntityDisplay;

class EntityViewMode extends EntityDisplayMultipleBase {

  /**
   * @var string
   */
  protected $viewMode;

  /**
   * @param string $view_mode
   */
  function __construct($view_mode) {
    $this->viewMode = $view_mode;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *
   * @see entity_view()
   * @see node_view_multiple()
   */
  function buildMultiple($entity_type, array $entities) {
    if (empty($entities)) {
      // entity_view() does not like an empty array of entities.
      // Especially, node_view_multiple() really does not.
      return array();
    }
    $builds_by_type = entity_view($entity_type, $entities, $this->viewMode);
    $builds_by_etid = $builds_by_type[$entity_type];
    $builds_by_delta = array();
    foreach ($entities as $delta => $entity) {
      $etid = entity_id($entity_type, $entity);
      if (!isset($builds_by_etid[$etid])) {
        continue;
      }
      $builds_by_delta[$delta] = $builds_by_etid[$etid];
    }
    return $builds_by_delta;
  }
}
