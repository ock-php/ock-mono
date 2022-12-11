<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Drupal\Component\Render\MarkupInterface;

class FormatorD8_RenderElement implements FormatorD8Interface {

  /**
   * @var array
   */
  private $element;

  /**
   * @param array $element
   */
  public function __construct(array $element) {
    $this->element = $element;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {

    return $this->element;
  }
}
