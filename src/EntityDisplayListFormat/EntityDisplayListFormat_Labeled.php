<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityDisplayListFormat;

use Drupal\renderkit8\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface;

class EntityDisplayListFormat_Labeled implements EntityDisplayListFormatInterface {

  /**
   * @var \Drupal\renderkit8\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface
   */
  private $labeledEntityDisplayListFormat;

  /**
   * @var string
   */
  private $label;

  /**
   * @param \Drupal\renderkit8\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
   * @param string $label
   */
  public function __construct(LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat, $label) {
    $this->labeledEntityDisplayListFormat = $labeledEntityDisplayListFormat;
    $this->label = $label;
  }

  /**
   * @param array[] $builds
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildListWithEntity(array $builds, $entityType, $entity) {
    return $this->labeledEntityDisplayListFormat->build($builds,$entityType, $entity, $this->label);
  }
}
