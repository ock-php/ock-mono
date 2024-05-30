<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityCondition\EntityConditionInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * @CfrPlugin(
 *   id = "conditional",
 *   label = "Conditional"
 * )
 */
class EntityDisplay_EntityCondition implements EntityDisplayInterface {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null
   */
  private ?EntityDisplayInterface $displayIfFalse;

  /**
   * @param \Drupal\renderkit\EntityCondition\EntityConditionInterface $condition
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $displayIfTrue
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null $displayIfFalse
   */
  public function __construct(
    private readonly EntityConditionInterface $condition,
    private readonly EntityDisplayInterface $displayIfTrue,
    EntityDisplayInterface $displayIfFalse = NULL
  ) {
    $this->displayIfFalse = $displayIfFalse;
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntities(array $entities): array {

    if ([] === $entities) {
      return [];
    }

    $entitiesWithQuality = [];
    $entitiesWithoutQuality = [];
    foreach ($entities as $delta => $entity) {
      if ($this->condition->entityCheckCondition($entity)) {
        $entitiesWithQuality[$delta] = $entity;
      }
      else {
        $entitiesWithoutQuality[$delta] = $entity;
      }
    }

    $buildsTrue = [] !== $entitiesWithQuality
      ? $this->displayIfTrue->buildEntities($entitiesWithQuality)
      : [];

    $buildsFalse = ([] !== $entitiesWithoutQuality && NULL !== $this->displayIfFalse)
      ? $this->displayIfFalse->buildEntities($entitiesWithoutQuality)
      : [];

    if ([] === $buildsTrue) {
      return $buildsFalse;
    }

    if ([] === $buildsFalse) {
      return $buildsTrue;
    }

    $deltas = array_keys($entities);
    $builds = array_fill_keys($deltas, []);

    return array_replace($builds, $buildsTrue, $buildsFalse);
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {

    if ($this->condition->entityCheckCondition($entity)) {
      return $this->displayIfTrue->buildEntity($entity);
    }

    if (NULL !== $this->displayIfFalse) {
      return $this->displayIfFalse->buildEntity($entity);
    }

    return [];
  }
}
