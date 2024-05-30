<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityImage;

use Drupal\Core\Entity\EntityInterface;

class EntityImage_EntityLabelAsAltFallback implements EntityImageInterface {

  /**
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $decorated
   */
  public function __construct(
    private readonly EntityImageInterface $decorated,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {

    $image = $this->decorated->buildEntity($entity);

    if (empty($image)) {
      return [];
    }

    if (empty($image['alt'])) {
      $image['alt'] = $entity->label();
    }

    return $image;
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntities(array $entities): array {

    $label = NULL;

    $images = [];
    foreach ($this->decorated->buildEntities($entities) as $delta => $image) {

      if (empty($image) || empty($entities[$delta])) {
        continue;
      }

      if (empty($image['alt'])) {
        $image['alt'] = $label ?? $label = $entities[$delta]->label();
      }
    }

    return $images;
  }
}
