<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\Component\Render\MarkupInterface;

class FieldDisplayProcessor_CustomLabel implements FieldDisplayProcessorInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface $decorated
   * @param string|\Drupal\Component\Render\MarkupInterface $customLabel
   */
  public function __construct(
    private readonly FieldDisplayProcessorInterface $decorated,
    private readonly string|MarkupInterface $customLabel,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function process(array $element): array {
    $element['#title'] = $this->customLabel;
    return $this->decorated->process($element);
  }

}
