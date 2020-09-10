<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface;

class FormatorD7_Boolean implements FormatorD7Interface {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface $schema
   *
   * @return self
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_BooleanInterface $schema
  ): FormatorD7_Boolean {
    return new self();
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

    return [
      '#title' => $label,
      /* @see \Drupal\Core\Render\Element\Checkbox */
      '#type' => 'checkbox',
      '#default_value' => !empty($conf),
    ];
  }
}
