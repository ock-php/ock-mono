<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\Component\Utility\Html;

class FieldDisplayProcessor_Label implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  private $decorated;

  /**
   * @var string|null
   */
  private $labelUnsafe;

  /**
   * @var bool
   */
  private $appendLabelColon = TRUE;

  /**
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface $decorated
   */
  public function __construct(FieldDisplayProcessorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $labelUnsafe
   *
   * @return static
   */
  public function withCustomLabel($labelUnsafe): self {
    $clone = clone $this;
    $clone->labelUnsafe = $labelUnsafe;
    return $clone;
  }

  /**
   * @return static
   */
  public function withoutLabelColon(): self {
    $clone = clone $this;
    $clone->appendLabelColon = FALSE;
    return $clone;
  }

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

    if ([] === $modified = $this->decorated->process($element)) {
      return [];
    }

    if (!isset($element['#label_display']) || 'hidden' === $element['#label_display']) {
      return $modified;
    }

    if (NULL !== $this->labelUnsafe) {
      $label_safe = Html::escape($this->labelUnsafe);
    }
    elseif (isset($element['#title']) && '' !== $element['#title']) {
      $label_safe = Html::escape($element['#title']);
    }
    else {
      return $modified;
    }

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
