<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\IntFormat;

use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Donquixote\Ock\Tests\Fixture\NumberFormat\NumberFormatInterface;

/**
 * Adapter from NumberFormat to IntFormat.
 */
#[OckPluginInstance("numberFormat", "Number format adapter")]
// @todo Mark as adapter.
class IntFormat_NumberFormat implements IntFormatInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Tests\Fixture\NumberFormat\NumberFormatInterface $numberFormat
   */
  public function __construct(
    #[OckOption('number_format', 'Number format')]
    private readonly NumberFormatInterface $numberFormat,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function format(int $number): string {
    return $this->numberFormat->format($number);
  }

}
