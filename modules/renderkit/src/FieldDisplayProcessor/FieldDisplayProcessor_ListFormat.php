<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\renderkit\ListFormat\ListFormatInterface;
use Ock\Ock\Attribute\Parameter\Checkbox;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

class FieldDisplayProcessor_ListFormat implements FieldDisplayProcessorInterface {

  /**
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   * @param bool $withFieldClasses
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  #[OckPluginInstance('listFormatPlus', 'List format +')]
  public static function create(
    #[OckOption('list_format', 'List format')]
    ListFormatInterface $listFormat,
    #[OckOption('with_field_classes', 'Add field classes')]
    #[Checkbox]
    bool $withFieldClasses = FALSE,
  ): FieldDisplayProcessorInterface {
    $fieldDisplayProcessor = new self($listFormat);
    if ($withFieldClasses) {
      $fieldDisplayProcessor = new FieldDisplayProcessor_FieldClasses($fieldDisplayProcessor);
    }
    return $fieldDisplayProcessor;
  }

  /**
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(
    private readonly ListFormatInterface $listFormat,
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
    return $this->listFormat->buildList($builds);
  }

}
