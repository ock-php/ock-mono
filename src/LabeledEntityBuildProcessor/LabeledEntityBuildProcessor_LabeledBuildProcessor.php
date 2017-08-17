<?php

namespace Drupal\renderkit8\LabeledEntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit8\LabeledFormat\LabeledFormatInterface;

/**
 * Implementation that ignores the entity.
 *
 * @CfrPlugin(
 *   id = "withoutEntity",
 *   label = "Without entity",
 *   inline = true
 * )
 */
class LabeledEntityBuildProcessor_LabeledBuildProcessor implements LabeledEntityBuildProcessorInterface {

  /**
   * @var \Drupal\renderkit8\LabeledFormat\LabeledFormatInterface
   */
  private $labeledFormat;

  /**
   * @param \Drupal\renderkit8\LabeledFormat\LabeledFormatInterface $labeledFormat
   */
  public function __construct(LabeledFormatInterface $labeledFormat) {
    $this->labeledFormat = $labeledFormat;
  }

  /**
   * @param array $build
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $label
   *
   * @return array
   */
  public function buildAddLabelWithEntity(array $build, EntityInterface $entity, $label) {
    return $this->labeledFormat->buildAddLabel($build, $label);
  }
}
