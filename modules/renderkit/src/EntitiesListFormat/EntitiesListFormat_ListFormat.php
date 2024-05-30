<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntitiesListFormat;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\ListFormat\ListFormatInterface;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('listFormat', 'List format with entity display')]
class EntitiesListFormat_ListFormat implements EntitiesListFormatInterface {

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(
    #[OckOption('entity_display', 'Entity display')]
    private readonly EntityDisplayInterface $entityDisplay,
    #[OckOption('list_format', 'List format')]
    private readonly ListFormatInterface $listFormat,
  ) {}

  /**
   * Displays the entities as a list, e.g. as a table.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array
   */
  public function entitiesBuildList(array $entities): array {
    $builds = $this->entityDisplay->buildEntities($entities);
    return $this->listFormat->buildList($builds);
  }
}
