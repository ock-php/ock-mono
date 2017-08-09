<?php

namespace Drupal\renderkit8\LabeledEntityDisplayListFormat;

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
  public function build(array $builds, $entityType, $entity, $label);

}
