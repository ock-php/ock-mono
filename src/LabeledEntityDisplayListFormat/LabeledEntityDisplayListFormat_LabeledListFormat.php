<?php

namespace Drupal\renderkit\LabeledEntityDisplayListFormat;

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
   * @var \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface
   */
  private $labeledListFormat;

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   */
  public function __construct(LabeledListFormatInterface $labeledListFormat) {
    $this->labeledListFormat = $labeledListFormat;
  }

  /**
   * @param array[] $builds
   *   Render arrays, e.g. for field items or field group children.
   * @param string $entityType
   * @param object $entity
   * @param string $label
   *   A label, e.g. for a field or field group.
   *
   * @return array
   *   Combined render array.
   */
  public function build(array $builds, $entityType, $entity, $label) {
    return $this->labeledListFormat->build($builds, $label);
  }
}
