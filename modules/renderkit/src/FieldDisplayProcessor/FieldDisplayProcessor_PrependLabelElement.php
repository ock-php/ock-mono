<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\Component\Utility\Html;

class FieldDisplayProcessor_PrependLabelElement implements FieldDisplayProcessorInterface {

  /**
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface $decorated
   */
  public function __construct(
    private readonly FieldDisplayProcessorInterface $decorated,
    private readonly bool $appendLabelColon = FALSE,
  ) {}

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   *
   * @see theme_ds_field_minimal()
   * @see theme_field()
   */
  public function process(array $element): array {
    $label = $element['#title'] ?? '';
    $element['#title'] = NULL;

    if ($label === '') {
      return $this->decorated->process($element);
    }

    if ([] === $modified = $this->decorated->process($element)) {
      return [];
    }

    if (($element['#label_display'] ?? 'hidden') === 'hidden') {
      return $modified;
    }

    $label = $element['#title'] ?? '';

    if ($label === '') {
      return $modified;
    }

    $label_safe = Html::escape($label);

    if ($this->appendLabelColon) {
      $label_safe .= ':&nbsp;';
    }

    return [
      'label' => [
        '#weight' => -100,
        '#type' => 'container',
        '#attributes' => ['class' => ['label-' . $element['#label_display']]],
        '#children' => $label_safe,
      ],
      'items' => $modified,
    ];
  }

}
