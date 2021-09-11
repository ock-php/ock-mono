<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;

class FormatorD8_Optionless implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return self
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ Formula_OptionlessInterface $formula
  ): FormatorD8_Optionless {
    return new self();
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD8Interface {
    return new FormatorD8_Boolean();
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {
    return [];
  }
}
