<?php
declare(strict_types=1);

namespace Donquixote\Cf\Optionlessness;

class Optionlessness implements OptionlessnessInterface {

  /**
   * @var bool
   */
  private $optionless;

  /**
   * @param bool $optionless
   */
  public function __construct($optionless) {
    $this->optionless = $optionless;
  }

  /**
   * {@inheritdoc}
   */
  public function isOptionless(): bool {
    return $this->optionless;
  }
}
