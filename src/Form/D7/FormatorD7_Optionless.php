<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;

class FormatorD7_Optionless implements FormatorD7Interface, OptionableFormatorD7Interface {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return self
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_OptionlessInterface $schema
  ): FormatorD7_Optionless {
    return new self();
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD7Interface {
    return new FormatorD7_Bool();
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {
    return [];
  }
}
