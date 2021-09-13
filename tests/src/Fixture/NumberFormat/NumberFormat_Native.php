<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Fixture\NumberFormat;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Primitive\Formula_Int;
use Donquixote\Ock\Text\Text;

/**
 * Calls PHP's built-in function number_format().
 */
class NumberFormat_Native implements NumberFormatInterface {

  /**
   * @var int
   */
  private $decimals;

  /**
   * @var string
   */
  private $decimalSeparator;

  /**
   * @var string
   */
  private $thousandsSeparator;

  /**
   * @obck("native", "Call built-in number_format()")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add('decimals', new Formula_Int(), Text::t('Decimals'))
      ->add(
        'format',
        Formula::flatSelect()
          ->add('.,', Text::t('US'))
          ->add(',.', Text::t('German'))
          ->create(),
        Text::t('Format'))
      ->call([self::class, 'create']);
  }

  /**
   * Static factory.
   *
   * @param int $decimals
   *   Number of decimals.
   * @param string $format
   *   One of 'de', 'us'.
   *
   * @return static
   */
  public static function create(int $decimals, string $format): self {
    return new self(
      $decimals,
      $format[0] ?? '.',
      $format[1] ?? ',');
  }

  /**
   * Constructor.
   *
   * @param int $decimals
   *   Number of decimals.
   * @param string $decimalSeparator
   *   Decimal separator.
   * @param string $thousandsSeparator
   *   Thousands separator.
   */
  public function __construct(int $decimals = 0, string $decimalSeparator = '.', string $thousandsSeparator = ',') {
    $this->decimals = $decimals;
    $this->decimalSeparator = $decimalSeparator;
    $this->thousandsSeparator = $thousandsSeparator;
  }

  /**
   * @param float|int $number
   *
   * @return string
   */
  public function format($number): string {
    return number_format(
      $number,
      $this->decimals,
      $this->decimalSeparator,
      $this->thousandsSeparator);
  }

}
