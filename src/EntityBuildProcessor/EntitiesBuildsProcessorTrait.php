<?php

namespace Drupal\renderkit\EntityBuildProcessor;

trait EntitiesBuildsProcessorTrait {

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  final function processEntityBuild(array $build, $entity_type, $entity) {
    if (empty($build)) {
      return array();
    }
    $builds = $this->processEntitiesBuilds(array($build), $entity_type, array($entity));
    return isset($builds[0])
      ? $builds[0]
      : array();
  }

  /**
   * @param array[] $builds
   *   The render arrays produced by the decorated display handler.
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   Modified render arrays for the given entities.
   *
   * @see \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface::processEntitiesBuilds()
   */
  abstract function processEntitiesBuilds(array $builds, $entity_type, array $entities);

}
