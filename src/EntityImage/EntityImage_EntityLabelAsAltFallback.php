<?php

namespace Drupal\renderkit8\EntityImage;

use Drupal\Core\Entity\EntityInterface;

class EntityImage_EntityLabelAsAltFallback implements EntityImageInterface {

  /**
   * @var \Drupal\renderkit8\EntityImage\EntityImageInterface
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit8\EntityImage\EntityImageInterface $decorated
   */
  public function __construct(EntityImageInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {

    $image = $this->decorated->buildEntity($entity);

    if (empty($image)) {
      return [];
    }

    if (empty($image['alt'])) {
      $image['alt'] = entity_label($entity_type, $entity);
    }

    return $image;
  }

  /**
   * Same method signature as in parent interface, just a different description.
   *
   * @param string $entityType
   *   E.g. 'node' or 'taxonomy_term'.
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *   Each render array must contain '#theme' => 'image'.
   */
  public function buildEntities($entityType, array $entities) {

    $label = NULL;

    $images = [];
    foreach ($this->decorated->buildEntities($entityType, $entities) as $delta => $image) {

      if (empty($image) || empty($entities[$delta])) {
        continue;
      }

      if (empty($image['alt'])) {
        $image['alt'] = NULL !== $label
          ? $label
          : $label = entity_label($entityType, $entities[$delta]);
      }
    }

    return $images;
  }
}
