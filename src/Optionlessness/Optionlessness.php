<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Optionlessness;

class Optionlessness implements OptionlessnessInterface {

  /**
   * @var bool
   */
  private $optionless;

  /**
   * @param bool $optionless
   */
  public function __construct(bool $optionless) {
    $this->optionless = $optionless;
  }

  /**
   * {@inheritdoc}
   */
  public function isOptionless(): bool {
    return $this->optionless;
  }
}
