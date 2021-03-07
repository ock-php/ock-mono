<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\IntFormat;

use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormatInterface;

/**
 * Adapter from NumberFormat to IntFormat.
 *
 * @ocui("numberFormat", "Number format adapter", adapter = true)
 */
class IntFormat_NumberFormat implements IntFormatInterface {

  /**
   * @var \Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormatInterface
   */
  private $numberFormat;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormatInterface $numberFormat
   */
  public function __construct(NumberFormatInterface $numberFormat) {
    $this->numberFormat = $numberFormat;
  }

  /**
   * {@inheritdoc}
   */
  public function format(int $number): string {
    return $this->numberFormat->format($number);
  }

}
