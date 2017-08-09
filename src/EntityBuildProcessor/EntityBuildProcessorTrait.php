<?php

namespace Drupal\renderkit8\EntityBuildProcessor;

trait EntityBuildProcessorTrait {

  /**
   * @param array[] $builds
   *   The render arrays produced by the decorated display handler.
   * @param string $entity_type
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   *   Modified render arrays for the given entities.
   */
  final public function processEntitiesBuilds(array $builds, $entity_type, array $entities) {
    foreach ($entities as $delta => $entity) {
      if (!empty($builds[$delta])) {
        $builds[$delta] = $this->processEntityBuild($builds[$delta], $entity_type, $entity);
      }
    }
    return $builds;
  }

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param string $entity_type
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Modified render array for the given entity.
   *
   * @see \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface::processEntityBuild()
   */
  abstract public function processEntityBuild(array $build, $entity_type, $entity);

}
