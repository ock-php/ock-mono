<?php

namespace Drupal\renderkit\EntityToEntities;

interface EntityToEntitiesInterface {

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[][]
   */
  function entitiesGetRelated($entityType, array $entities);

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object[]
   */
  function entityGetRelated($entityType, $entity);
}
