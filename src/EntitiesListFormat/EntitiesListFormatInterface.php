<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntitiesListFormat;

interface EntitiesListFormatInterface {

  /**
   * Displays the entities as a list, e.g. as a table.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array
   */
  public function entitiesBuildList(array $entities);

}
