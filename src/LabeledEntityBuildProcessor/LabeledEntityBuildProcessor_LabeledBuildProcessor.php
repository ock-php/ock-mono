<?php

namespace Drupal\renderkit8\LabeledEntityBuildProcessor;

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
   * @param string $entityType
   * @param object $entity
   * @param string $label
   *
   * @return array
   */
  public function buildAddLabelWithEntity(array $build, $entityType, $entity, $label) {
    return $this->labeledFormat->buildAddLabel($build, $label);
  }
}
