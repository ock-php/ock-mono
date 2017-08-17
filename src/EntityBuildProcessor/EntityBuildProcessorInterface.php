<?php

namespace Drupal\renderkit8\EntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;

interface EntityBuildProcessorInterface {

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  public function processEntityBuild(array $build, EntityInterface $entity);
}
