<?php

declare(strict_types=1);

namespace Donquixote\Ock\Optionlessness;

use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\FormulaAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

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
   * Determines whether a formula is optionless.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return bool
   */
  public static function checkFormula(FormulaInterface $formula, UniversalAdapterInterface $universalAdapter): bool {
    try {
      return self::fromFormula($formula, $universalAdapter)->isOptionless();
    }
    catch (AdapterException) {
      // Assume it is not optionless.
      return FALSE;
    }
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\Optionlessness\OptionlessnessInterface
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public static function fromFormula(FormulaInterface $formula, UniversalAdapterInterface $universalAdapter): OptionlessnessInterface {
    return FormulaAdapter::requireObject(
      $formula,
      OptionlessnessInterface::class,
      $universalAdapter,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isOptionless(): bool {
    return $this->optionless;
  }

}
