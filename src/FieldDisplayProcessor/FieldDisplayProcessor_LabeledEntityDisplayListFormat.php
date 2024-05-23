<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Donquixote\Ock\Attribute\Parameter\Checkbox;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface;

class FieldDisplayProcessor_LabeledEntityDisplayListFormat implements FieldDisplayProcessorInterface {

  /**
   * @param \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
   */
  public function __construct(
    private readonly LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat,
  ) {}

  /**
   * @param \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
   * @param bool $withFieldClasses
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  #[OckPluginInstance('labeledEntityDisplayListFormatPlus', 'Labeled entity display list format +')]
  public static function create(
    #[OckOption('labeled_entity_display_list_format', 'Labeled list format')]
    LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat,
    #[OckOption('with_field_classes', 'Add field classes')]
    #[Checkbox] bool $withFieldClasses = FALSE,
  ): FieldDisplayProcessorInterface {
    $fieldDisplayProcessor = new self($labeledEntityDisplayListFormat);
    if ($withFieldClasses) {
      $fieldDisplayProcessor = new FieldDisplayProcessor_FieldClasses($fieldDisplayProcessor);
    }
    return $fieldDisplayProcessor;
  }

  /**
   * @param array|array[][] $element
   *   Format:
   *     ['#theme' => 'field', '#items' => [..], ..]
   *     $['#items'][$delta] = $item
   *
   * @return array
   */
  public function process(array $element): array {
    $builds = [];
    foreach ($element['#items'] as $delta => $item) {
      if (!empty($element[$delta])) {
        $builds[$delta] = $element[$delta];
      }
    }
    $entityType = $element['#entity_type'];
    $entity = $element['#object'];
    return $this->labeledEntityDisplayListFormat->build(
      $builds,
      $entityType,
      $entity,
      $element['#title'],
    );
  }
}
