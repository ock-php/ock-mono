<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityToEntity\EntityToEntityInterface;

/**
 * Gets a referenced entity.
 *
 * @todo Register this with ock somehow.
 */
class EntityDisplay_Reference implements EntityDisplayInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $reference
   */
  public function __construct(
    private readonly EntityDisplayInterface $decorated,
    private readonly EntityToEntityInterface $reference,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildEntities(array $entities): array {

    $relatedEntities = [];
    foreach ($entities as $delta => $entity) {
      if (NULL !== $related = $this->reference->entityGetRelated($entity)) {
        $relatedEntities[$delta] = $related;
      }
    }

    return $this->decorated->buildEntities($relatedEntities);
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {

    if (NULL === $related = $this->reference->entityGetRelated($entity)) {
      return [];
    }

    return $this->decorated->buildEntity($related);
  }
}
