<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface;

class FormatorD8_Boolean implements FormatorD8Interface {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface $schema
   *
   * @return self
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_BooleanInterface $schema
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
