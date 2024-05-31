<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface;
use Drupal\renderkit\EntityToEntities\EntityToEntitiesInterface;
use Drupal\renderkit\Util\ExceptionUtil;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('listOfRelatedEntities', 'Related entities list')]
class EntityDisplay_ListOfRelatedEntities extends EntitiesDisplayBase {

  /**
   * @param \Drupal\renderkit\EntityToEntities\EntityToEntitiesInterface $entityToEntities
   * @param \Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface $entitiesListFormat
   */
  public function __construct(
    #[OckOption('entityToEntities', 'Entity to entities')]
    private readonly EntityToEntitiesInterface $entityToEntities,
    #[OckOption('entitiesListFormat', 'Entities list format')]
    private readonly EntitiesListFormatInterface $entitiesListFormat,
  ) {}

  /**
   * {@inheritdoc}
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
