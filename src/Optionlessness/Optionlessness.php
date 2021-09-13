<?php
declare(strict_types=1);

namespace Donquixote\Ock\Optionlessness;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

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
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $nursery
   *
   * @return bool
   */
  public static function checkFormula(FormulaInterface $formula, IncarnatorInterface $nursery): bool {
    try {
      return self::fromFormula($formula, $nursery)->isOptionless();
    }
    catch (IncarnatorException $e) {
      // Assume it is not optionless.
      return FALSE;
    }
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $nursery
   *
   * @return \Donquixote\Ock\Optionlessness\OptionlessnessInterface
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromFormula(FormulaInterface $formula, IncarnatorInterface $nursery): OptionlessnessInterface {
    /** @var \Donquixote\Ock\Optionlessness\OptionlessnessInterface $candidate */
    $candidate = $nursery->incarnate($formula, OptionlessnessInterface::class);
    return $candidate;
  }

  /**
   * {@inheritdoc}
   */
  public function isOptionless(): bool {
    return $this->optionless;
  }

}
