<?php

namespace Drupal\renderkit\EntityDisplay\Wrapper;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class NeutralEntityWrapper implements EntityDisplayInterface {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null
   */
  private $decorated;

  /**
   * Allow the same object to act as a decorator instead of a processor.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   *
   * @return $this
   */
  function decorate(EntityDisplayInterface $decorated) {
    $this->decorated = $decorated;
    return $this;
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
   * @param string $entity_type
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildMultiple($entity_type, array $entities) {
    return isset($this->decorated)
      ? $this->decorated->buildMultiple($entity_type, $entities)
      : array();
  }
}
