<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledEntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\LabeledFormat\LabeledFormatInterface;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Implementation that ignores the entity.
 *
 * @todo Mark as adapter.
 */
#[OckPluginInstance('withoutEntity', 'Without entity')]
class LabeledEntityBuildProcessor_LabeledBuildProcessor implements LabeledEntityBuildProcessorInterface {

  /**
   * @param \Drupal\renderkit\LabeledFormat\LabeledFormatInterface $labeledFormat
   */
  public function __construct(
    #[OckOption('labeledFormat', 'Labeled format')]
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
