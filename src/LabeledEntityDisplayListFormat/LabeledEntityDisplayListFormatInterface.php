<?php

namespace Drupal\renderkit\LabeledEntityDisplayListFormat;

interface LabeledEntityDisplayListFormatInterface {

  /**
   * @param array[] $builds
   *   Render arrays, e.g. for field items or field group children.
   * @param string $entityType
   * @param object $entity
   * @param string $label
   *   A label, e.g. for a field or field group.
   *
   * @return array
   *   Combined render array.
   */
  function build(array $builds, $entityType, $entity, $label);

}
