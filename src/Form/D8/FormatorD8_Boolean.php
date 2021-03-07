<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

use Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface;

class FormatorD8_Boolean implements FormatorD8Interface {

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface $formula
   *
   * @return self
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ Formula_BooleanInterface $formula
  ): FormatorD8_Boolean {
    return new self();
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    return [
      '#title' => $label,
      /* @see \Drupal\Core\Render\Element\Checkbox */
      '#type' => 'checkbox',
      '#default_value' => !empty($conf),
    ];
  }
}
