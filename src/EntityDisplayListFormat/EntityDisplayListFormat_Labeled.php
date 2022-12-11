<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplayListFormat;

use Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface;

class EntityDisplayListFormat_Labeled implements EntityDisplayListFormatInterface {

  /**
   * @var \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface
   */
  private $labeledEntityDisplayListFormat;

  /**
   * @param \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
   * @param string $label
   */
  public function __construct(LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat, private $label) {
    $this->labeledEntityDisplayListFormat = $labeledEntityDisplayListFormat;
  }

  /**
   * @param array[] $builds
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildListWithEntity(array $builds, $entityType, $entity): array {
    return $this->labeledEntityDisplayListFormat->build($builds,$entityType, $entity, $this->label);
  }
}
