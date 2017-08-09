<?php

namespace Drupal\renderkit8\EntitiesListFormat;

interface EntitiesListFormatInterface {

  /**
   * Displays the entities as a list, e.g. as a table.
   *
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array
   *   A render array.
   */
  public function entitiesBuildList($entityType, array $entities);

}
