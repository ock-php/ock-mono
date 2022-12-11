<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Ock\Formula\Boolean\Formula_BooleanInterface;
use Drupal\Component\Render\MarkupInterface;

class FormatorD8_Boolean implements FormatorD8Interface {

  /**
   * @param \Donquixote\Ock\Formula\Boolean\Formula_BooleanInterface $formula
   *
   * @return self
   */
  #[Adapter]
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ #[Adaptee] Formula_BooleanInterface $formula
  ): FormatorD8_Boolean {
    return new self();
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
    return [
      '#title' => $label,
      /* @see \Drupal\Core\Render\Element\Checkbox */
      '#type' => 'checkbox',
      '#default_value' => !empty($conf),
    ];
  }
}
