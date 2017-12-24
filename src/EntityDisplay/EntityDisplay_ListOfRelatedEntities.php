<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\renderkit8\EntitiesListFormat\EntitiesListFormatInterface;
use Drupal\renderkit8\EntityToEntities\EntityToEntitiesInterface;
use Drupal\renderkit8\Util\ExceptionUtil;

/**
 * @CfrPlugin("listOfRelatedEntities", "Related entities list")
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
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   */
  public function buildEntities(array $entities) {

    $targetEntitiess = $this->entityToEntities->entitiesGetRelated($entities);

    $builds = [];
    foreach ($targetEntitiess as $delta => $targetEntities) {

      if (!\is_array($targetEntities)) {
        throw new \RuntimeException(
          ExceptionUtil::formatMessage(
            '@method is expected to return EntityInterface[][], found ?instead at $[?delta].',
            [
              '@method' => \get_class($this->entityToEntities) . '->entitiesGetRelated()',
              '?instead' => $targetEntities,
              '?delta' => $delta,
            ])
          );
      }

      $builds[$delta] = $this->entitiesListFormat->entitiesBuildList($targetEntities);
    }

    return $builds;
  }
}
