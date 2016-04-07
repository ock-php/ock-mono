<?php

namespace Drupal\renderkit\LabeledEntityBuildProcessor;

use Drupal\renderkit\LabeledFormat\LabeledFormatInterface;

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
   * @var \Drupal\renderkit\LabeledFormat\LabeledFormatInterface
   */
  private $labeledFormat;

  /**
   * @param \Drupal\renderkit\LabeledFormat\LabeledFormatInterface $labeledFormat
   */
  function __construct(LabeledFormatInterface $labeledFormat) {
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
  function buildAddLabelWithEntity(array $build, $entityType, $entity, $label) {
    return $this->labeledFormat->buildAddLabel($build, $label);
  }
}
