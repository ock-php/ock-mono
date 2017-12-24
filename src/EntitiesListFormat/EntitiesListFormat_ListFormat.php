<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntitiesListFormat;

use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit8\ListFormat\ListFormatInterface;

/**
 * @CfrPlugin("listFormat", "List format")
 */
class EntitiesListFormat_ListFormat implements EntitiesListFormatInterface {

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  private $entityDisplay;

  /**
   * @var \Drupal\renderkit8\ListFormat\ListFormatInterface
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit8\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(EntityDisplayInterface $entityDisplay, ListFormatInterface $listFormat) {
    $this->entityDisplay = $entityDisplay;
    $this->listFormat = $listFormat;
  }

  /**
   * Displays the entities as a list, e.g. as a table.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array
   */
  public function entitiesBuildList(array $entities) {
    $builds = $this->entityDisplay->buildEntities($entities);
    return $this->listFormat->buildList($builds);
  }
}
