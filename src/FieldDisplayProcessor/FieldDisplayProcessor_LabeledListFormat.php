<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Donquixote\Ock\Attribute\Parameter\Checkbox;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface;

class FieldDisplayProcessor_LabeledListFormat implements FieldDisplayProcessorInterface {

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   * @param bool $withFieldClasses
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  #[OckPluginInstance('labeledListFormatPlus', 'Labeled list format +')]
  public static function create(
    #[OckOption('labeled_list_format', 'Labeled list format')]
    LabeledListFormatInterface $labeledListFormat,
    #[OckOption('with_field_classes', 'Add field classes')]
    #[Checkbox] bool $withFieldClasses = FALSE,
  ): FieldDisplayProcessorInterface {
    $fieldDisplayProcessor = new self($labeledListFormat);
    if ($withFieldClasses) {
      $fieldDisplayProcessor = new FieldDisplayProcessor_FieldClasses($fieldDisplayProcessor);
    }
    return $fieldDisplayProcessor;
  }

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   */
  public function __construct(
    private readonly LabeledListFormatInterface $labeledListFormat,
  ) {}

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
    return $this->labeledListFormat->build($builds, $element['#title']);
  }
}
