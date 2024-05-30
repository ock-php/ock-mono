<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledEntityDisplayListFormat;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface;

/**
 * Implementation that does not use the entity.
 *
 * @CfrPlugin(
 *   id = "withoutEntity",
 *   label = "Without entity",
 *   inline = true
 * )
 */
class LabeledEntityDisplayListFormat_LabeledListFormat implements LabeledEntityDisplayListFormatInterface {

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   */
  public function __construct(
    private readonly LabeledListFormatInterface $labeledListFormat,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function build(array $builds, string $entityType, EntityInterface $entity, string $label): array {
    return $this->labeledListFormat->build($builds, $label);
  }
}
