<?php

namespace Drupal\renderkit8\EntityDisplayListFormat;

interface EntityDisplayListFormatInterface {

  /**
   * @param array[] $builds
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildListWithEntity(array $builds, $entityType, $entity);

}
