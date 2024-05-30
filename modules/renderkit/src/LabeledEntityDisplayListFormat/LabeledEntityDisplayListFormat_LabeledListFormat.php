<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledEntityDisplayListFormat;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface;
use Ock\Ock\Attribute\Parameter\OckAdaptee;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Implementation that does not use the entity.
 *
 * @todo Mark as adapter/inline.
 */
#[OckPluginInstance('withoutEntity', 'Without entity')]
class LabeledEntityDisplayListFormat_LabeledListFormat implements LabeledEntityDisplayListFormatInterface {

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   */
  public function __construct(
    #[OckAdaptee]
    private readonly LabeledListFormatInterface $labeledListFormat,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function build(array $builds, string $entityType, EntityInterface $entity, string $label): array {
    return $this->labeledListFormat->build($builds, $label);
  }
}
