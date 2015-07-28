<?php

namespace Drupal\renderkit\EntityBuildProcessor;

interface EntityBuildProcessorInterface {

  /**
   * @param array[] $builds
   *   The render arrays produced by the decorated display handler.
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   Modified render arrays for the given entities.
   */
  function processMultiple(array $builds, $entity_type, array $entities);
}
