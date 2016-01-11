<?php

namespace Drupal\renderkit\EntityDisplay;

use Drupal\renderkit\EntityToEntity\EntityToEntityInterface;

class EntityDisplay_Reference extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  private $decorated;

  /**
   * @var \Drupal\renderkit\EntityToEntity\EntityToEntityInterface
   */
  private $reference;

  /**
   * @param string $entityType
   *
   * @return array|bool
   */
  static function author($entityType) {
    switch ($entityType) {
      case 'node':
        return array(
          'propertyKey' => 'uid',
          'targetType' => 'user',
        );
    }

    return FALSE;
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $reference
   */
  function __construct(EntityDisplayInterface $decorated, EntityToEntityInterface $reference) {
    $this->decorated = $decorated;
    $this->reference = $reference;
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
   * @param string $entityType
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildEntities($entityType, array $entities) {
    $relatedEntities = $this->reference->entitiesGetRelated($entityType, $entities);
    return $this->decorated->buildEntities($this->reference->getTargetType(), $relatedEntities);
  }
}
