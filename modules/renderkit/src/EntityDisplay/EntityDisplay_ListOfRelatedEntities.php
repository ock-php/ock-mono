<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface;
use Drupal\renderkit\EntityToEntities\EntityToEntitiesInterface;
use Drupal\renderkit\Util\ExceptionUtil;

/**
 * @CfrPlugin("listOfRelatedEntities", "Related entities list")
 */
class EntityDisplay_ListOfRelatedEntities extends EntitiesDisplayBase {

  /**
   * @param \Drupal\renderkit\EntityToEntities\EntityToEntitiesInterface $entityToEntities
   * @param \Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface $entitiesListFormat
   */
  public function __construct(
    private readonly EntityToEntitiesInterface $entityToEntities,
    private readonly EntitiesListFormatInterface $entitiesListFormat,
  ) {}

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
  public function buildEntities(array $entities): array {

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
