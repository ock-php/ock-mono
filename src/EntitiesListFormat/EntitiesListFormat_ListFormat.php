<?php

namespace Drupal\renderkit\EntitiesListFormat;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\ListFormat\ListFormatInterface;

/**
 * @CfrPlugin("listFormat", "List format")
 */
class EntitiesListFormat_ListFormat implements EntitiesListFormatInterface {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  private $entityDisplay;

  /**
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(EntityDisplayInterface $entityDisplay, ListFormatInterface $listFormat) {
    $this->entityDisplay = $entityDisplay;
    $this->listFormat = $listFormat;
  }

  /**
   * Displays the entities as a list, e.g. as a table.
   *
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array
   *   A render array.
   */
  public function entitiesBuildList($entityType, array $entities) {
    $builds = $this->entityDisplay->buildEntities($entityType, $entities);
    return $this->listFormat->buildList($builds);
  }
}
