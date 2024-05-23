<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplayListFormat;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface;

class EntityDisplayListFormat_Labeled implements EntityDisplayListFormatInterface {

  /**
   * @param \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
   * @param string $label
   */
  public function __construct(
    private readonly LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat,
    private readonly string $label,
  ) {}

  /**
   * @param array[] $builds
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildListWithEntity(array $builds, string $entityType, EntityInterface $entity): array {
    return $this->labeledEntityDisplayListFormat->build($builds,$entityType, $entity, $this->label);
  }
}
