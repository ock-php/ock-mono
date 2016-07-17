<?php

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface;
use Drupal\renderkit\ListFormat\ListFormatInterface;

class FieldDisplayProcessor_FieldClasses implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  private $decorated;

  /**
   * @CfrPlugin("labeledListFormatWithFieldClasses", @t("Labeled list format + field classes"), inline = true)
   *
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function createFromLabeledListFormat(LabeledListFormatInterface $labeledListFormat) {
    return new self(new FieldDisplayProcessor_LabeledListFormat($labeledListFormat));
  }

  /**
   * @CfrPlugin("listFormatWithFieldClasses", @t("List format + field classes"), inline = true)
   *
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function createFromListFormat(ListFormatInterface $listFormat) {
    return new self(new FieldDisplayProcessor_ListFormat($listFormat));
  }

  /**
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface $decorated
   */
  public function __construct(FieldDisplayProcessorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  function process(array $element) {
    $build = $this->decorated->process($element);
    $build['#attributes']['class'][] = 'field';
    $build['#attributes']['class'][] = 'field-name-' . str_replace('_', '-', $element['#field_name']);
    $build['#attributes']['class'][] = 'field-type-' . str_replace('_', '-', $element['#field_type']);
    $build['#attributes']['class'][] = 'field-label-' . $element['#label_display'];
    return $build;
  }
}
