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
   * @var \Drupal\renderkit\EntityCondition\EntityConditionInterface
   */
  private $condition;

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  private $displayIfTrue;

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null
   */
  private $displayIfFalse;

  /**
   * @param \Drupal\renderkit\EntityCondition\EntityConditionInterface $condition
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $displayIfTrue
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null $displayIfFalse
   */
  public function __construct(
    EntityConditionInterface $condition,
    EntityDisplayInterface $displayIfTrue,
    EntityDisplayInterface $displayIfFalse = NULL
  ) {
    $this->condition = $condition;
    $this->displayIfTrue = $displayIfTrue;
    $this->displayIfFalse = $displayIfFalse;
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
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *
   * @see \Drupal\renderkit\EntityDisplay\EntityDisplayInterface::buildEntity()
   */
  public function buildEntity(EntityInterface $entity) {

    if ($this->condition->entityCheckCondition($entity)) {
      return $this->displayIfTrue->buildEntity($entity);
    }

    if (NULL !== $this->displayIfFalse) {
      return $this->displayIfFalse->buildEntity($entity);
    }

    return [];
  }
}
