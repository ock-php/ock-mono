<?php

namespace Drupal\renderkit\EntityDisplayListFormat;

interface EntityDisplayListFormatInterface {

  /**
   * @param array[] $builds
   * @param string $entityType
   * @param object $entity
   *
   * @return array
   */
  public function buildListWithEntity(array $builds, $entityType, $entity);

}
