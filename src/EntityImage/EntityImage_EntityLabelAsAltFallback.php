<?php

namespace Drupal\renderkit\EntityImage;

class EntityImage_EntityLabelAsAltFallback implements EntityImageInterface {

  /**
   * @var \Drupal\renderkit\EntityImage\EntityImageInterface
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $decorated
   */
  public function __construct(EntityImageInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param string $entity_type
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity($entity_type, $entity) {

    $image = $this->decorated->buildEntity($entity_type, $entity);

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
   * @param object[] $entities
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
