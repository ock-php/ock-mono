<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\NumberFormat;

use Donquixote\OCUI\Tests\Fixture\IntFormat\IntFormat_Trivial;
use Donquixote\OCUI\Tests\Fixture\IntFormat\IntFormatInterface;

/**
 * @ocui("intRounded", "Rounded as integer", adapter = true)
 */
class NumberFormat_IntRounded implements NumberFormatInterface {

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\IntFormat\IntFormatInterface
   */
  private $intFormat;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Tests\Fixture\IntFormat\IntFormatInterface|null $intFormat
   *   Integer format for the rounded number, or NULL for trivial format.
   */
  public function __construct(IntFormatInterface $intFormat = NULL) {
    $this->intFormat = $intFormat ?? new IntFormat_Trivial();
  }

  /**
   * {@inheritdoc}
   */
  public function format($number): string {
    return $this->intFormat->format(
      (int) round($number));
  }

}
