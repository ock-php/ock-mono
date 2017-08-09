<?php

namespace Drupal\renderkit8\EntityBuildProcessor;

interface EntityBuildProcessorInterface {

  /**
   * @param array[] $builds
   *   The render arrays produced by the decorated display handler.
   * @param string $entity_type
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   *   Modified render arrays for the given entities.
   */
  public function processEntitiesBuilds(array $builds, $entity_type, array $entities);

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param string $entity_type
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  public function processEntityBuild(array $build, $entity_type, $entity);
}
