<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Drupal\Component\Render\MarkupInterface;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;
use Ock\Adaptism\Attribute\Adapter;
use Ock\Ock\Formula\Optionless\Formula_OptionlessInterface;

class FormatorD8_Optionless implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @param \Ock\Ock\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return self
   */
  #[Adapter]
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
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
    return [];
  }

}
