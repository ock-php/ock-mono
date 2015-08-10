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
  function processEntitiesBuilds(array $builds, $entity_type, array $entities);

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  function processOne(array $build, $entity_type, $entity);
}
