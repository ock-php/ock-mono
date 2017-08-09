<?php

namespace Drupal\renderkit8\EntityImage;

use Drupal\Core\Entity\EntityInterface;

/**
 * @CfrPlugin(
 *   id = "fallback",
 *   label = @t("Fallback")
 * )
 */
class EntityImage_FallbackDecorator implements EntityImageInterface {

  /**
   * @var \Drupal\renderkit8\EntityImage\EntityImageInterface
   */
  private $decorated;

  /**
   * @var \Drupal\renderkit8\EntityImage\EntityImageInterface
   */
  private $fallback;

  /**
   * @param \Drupal\renderkit8\EntityImage\EntityImageInterface $decorated
   * @param \Drupal\renderkit8\EntityImage\EntityImageInterface $fallback
   */
  public function __construct(EntityImageInterface $decorated, EntityImageInterface $fallback) {
    $this->decorated = $decorated;
    $this->fallback = $fallback;
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
    $build = $this->decorated->buildEntity($entity);
    if (is_array($build) && [] !== $build) {
      return $build;
    }
    return $this->fallback->buildEntity($entity);
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
    $builds = array_fill_keys(array_keys($entities), NULL);
    $builds += $this->decorated->buildEntities($entityType, $entities);
    foreach (array_filter($this->decorated->buildEntities($entityType, $entities)) as $delta => $build) {
      unset($entities[$delta]);
      $builds[$delta] = $build;
    }
    foreach (array_filter($this->fallback->buildEntities($entityType, $entities)) as $delta => $build) {
      $builds[$delta] = $build;
    }
    return array_filter($builds);
  }
}
