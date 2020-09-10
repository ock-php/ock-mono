<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Form\D8\Optionable\OptionableFormatorD8Interface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;

class FormatorD8_Optionless implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return self
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_OptionlessInterface $schema
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
