<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\IntFormat;

use Donquixote\ObCK\Tests\Fixture\NumberFormat\NumberFormatInterface;

/**
 * Adapter from NumberFormat to IntFormat.
 *
 * @obck("numberFormat", "Number format adapter", adapter = true)
 */
class IntFormat_NumberFormat implements IntFormatInterface {

  /**
   * @var \Donquixote\ObCK\Tests\Fixture\NumberFormat\NumberFormatInterface
   */
  private $numberFormat;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Tests\Fixture\NumberFormat\NumberFormatInterface $numberFormat
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
