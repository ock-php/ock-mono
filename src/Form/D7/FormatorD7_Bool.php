<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

class FormatorD7_Bool implements FormatorD7Interface {

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

    return [
      '#type' => 'checkbox',
      '#label' => $label,
      '#default_value' => !empty($conf),
    ];
  }
}
