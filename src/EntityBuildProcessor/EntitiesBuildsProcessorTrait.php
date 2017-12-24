<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityBuildProcessor;

trait EntitiesBuildsProcessorTrait {

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param string $entity_type
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  final public function processEntityBuild(array $build, $entity_type, $entity) {
    if (empty($build)) {
      return [];
    }
    $builds = $this->processEntitiesBuilds([$build], $entity_type, [$entity]);
    return $builds[0] ?? [];
  }

  /**
   * @param array[] $builds
   *   The render arrays produced by the decorated display handler.
   * @param string $entity_type
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   *   Modified render arrays for the given entities.
   *
   * @see \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface::processEntitiesBuilds()
   */
  abstract public function processEntitiesBuilds(array $builds, $entity_type, array $entities);

}
