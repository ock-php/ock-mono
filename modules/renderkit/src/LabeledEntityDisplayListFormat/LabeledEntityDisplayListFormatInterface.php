<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledEntityDisplayListFormat;

use Drupal\Core\Entity\EntityInterface;

interface LabeledEntityDisplayListFormatInterface {

  /**
   * @param array[] $builds
   *   Render arrays, e.g. for field items or field group children.
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $label
   *   A label, e.g. for a field or field group.
   *
   * @return array
   *   Combined render array.
   */
  public function build(array $builds, string $entityType, EntityInterface $entity, string $label): array;

}
