<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledEntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;
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
   * @param \Drupal\renderkit\LabeledFormat\LabeledFormatInterface $labeledFormat
   */
  public function __construct(
    private readonly LabeledFormatInterface $labeledFormat,
  ) {}

  /**
   * @param array $build
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $label
   *
   * @return array
   */
  public function buildAddLabelWithEntity(array $build, EntityInterface $entity, string $label): array {
    return $this->labeledFormat->buildAddLabel($build, $label);
  }
}
