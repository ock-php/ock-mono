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
  function buildListWithEntity(array $builds, $entityType, $entity);

}
