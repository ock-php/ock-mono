<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\renderkit8\EntitiesListFormat\EntitiesListFormatInterface;
use Drupal\renderkit8\EntityToEntities\EntityToEntitiesInterface;

/**
 * @CfrPlugin("listOfRelatedEntities", "List of related entities")
 */
class EntityDisplay_ListOfRelatedEntities extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntityToEntities\EntityToEntitiesInterface
   */
  private $entityToEntities;

  /**
   * @var \Drupal\renderkit8\EntitiesListFormat\EntitiesListFormatInterface
   */
  private $entitiesListFormat;

  /**
   * @param \Drupal\renderkit8\EntityToEntities\EntityToEntitiesInterface $entityToEntities
   * @param \Drupal\renderkit8\EntitiesListFormat\EntitiesListFormatInterface $entitiesListFormat
   */
  public function __construct(EntityToEntitiesInterface $entityToEntities, EntitiesListFormatInterface $entitiesListFormat) {
    $this->entityToEntities = $entityToEntities;
    $this->entitiesListFormat = $entitiesListFormat;
  }

  /**
   * Builds render arrays from the entities provided.
   *
   * Both the entities and the resulting render arrays are in plural, to allow
   * for more performant implementations.
   *
   * Array keys and their order must be preserved, although implementations
   * might remove some keys that are empty.
   *
   * @param string $entityType
   *   E.g. 'node' or 'taxonomy_term'.
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  public function buildEntities($entityType, array $entities) {

    $targetEntitiess = $this->entityToEntities->entitiesGetRelated($entityType, $entities);

    $builds = [];
    foreach ($targetEntitiess as $delta => $targetEntities) {
      if (!is_array($targetEntities)) {
        dpm(get_defined_vars());
        break;
      }
      $builds[$delta] = $this->entitiesListFormat->entitiesBuildList($entityType, $targetEntities);
    }

    return $builds;
  }
}
