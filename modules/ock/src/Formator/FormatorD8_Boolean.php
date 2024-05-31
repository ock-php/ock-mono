<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Drupal\Component\Render\MarkupInterface;
use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;
use Ock\Ock\Formula\Boolean\Formula_BooleanInterface;

class FormatorD8_Boolean implements FormatorD8Interface {

  /**
   * @param \Ock\Ock\Formula\Boolean\Formula_BooleanInterface $formula
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
