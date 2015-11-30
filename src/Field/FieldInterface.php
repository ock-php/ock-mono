<?php

namespace Drupal\renderkit\Field;

interface FieldInterface {

  /**
   * @param object $entity
   *
   * @return array[][]
   *   Format: $[$langcode][$delta] = $item
   */
  function entityGetAllItems($entity);

  /**
   * @param object $entity
   * @param string $langcode
   *
   * @return array[][]
   *   Format: $[$delta] = $item
   */
  function entityLangGetItems($entity, $langcode = LANGUAGE_NONE);

}
