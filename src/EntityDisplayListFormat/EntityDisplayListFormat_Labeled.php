<?php

namespace Drupal\renderkit\EntityDisplayListFormat;

use Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface;

class EntityDisplayListFormat_Labeled implements EntityDisplayListFormatInterface {

  /**
   * @var \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface
   */
  private $labeledEntityDisplayListFormat;

  /**
   * @var string
   */
  private $label;

  /**
   * @param \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
   * @param string $label
   */
  public function __construct(LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat, $label) {
    $this->labeledEntityDisplayListFormat = $labeledEntityDisplayListFormat;
    $this->label = $label;
  }

  /**
   * @param array[] $builds
   * @param string $entityType
   * @param object $entity
   *
   * @return array
   */
  public function buildListWithEntity(array $builds, $entityType, $entity) {
    return $this->labeledEntityDisplayListFormat->build($builds,$entityType, $entity, $this->label);
  }
}
