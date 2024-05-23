<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityImage;

use Drupal\Core\Entity\EntityInterface;

/**
 * @CfrPlugin(
 *   id = "fallback",
 *   label = @t("Fallback")
 * )
 */
class EntityImage_FallbackDecorator implements EntityImageInterface {

  /**
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $decorated
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $fallback
   */
  public function __construct(
    private readonly EntityImageInterface $decorated,
    private readonly EntityImageInterface $fallback,
  ) {}

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity): array {
    $build = $this->decorated->buildEntity($entity);
    if (\is_array($build) && [] !== $build) {
      return $build;
    }
    return $this->fallback->buildEntity($entity);
  }

  /**
   * Same method signature as in parent interface, just a different description.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   */
  public function buildEntities(array $entities): array {
    $builds = array_fill_keys(array_keys($entities), NULL);
    $builds += $this->decorated->buildEntities($entities);
    foreach (array_filter($this->decorated->buildEntities($entities)) as $delta => $build) {
      unset($entities[$delta]);
      $builds[$delta] = $build;
    }
    foreach (array_filter($this->fallback->buildEntities($entities)) as $delta => $build) {
      $builds[$delta] = $build;
    }
    return array_filter($builds);
  }
}
