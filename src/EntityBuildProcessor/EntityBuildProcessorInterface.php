<?php

namespace Drupal\renderkit\EntityBuildProcessor;

interface EntityBuildProcessorInterface {

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  function process($build, $entity_type, $entity);
}
