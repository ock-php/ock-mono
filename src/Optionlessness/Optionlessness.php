<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Optionlessness;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Nursery\NurseryInterface;

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
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $nursery
   *
   * @return bool
   */
  public static function checkFormula(FormulaInterface $formula, NurseryInterface $nursery): bool {
    try {
      return self::fromFormula($formula, $nursery)->isOptionless();
    }
    catch (FormulaToAnythingException $e) {
      // Assume it is not optionless.
      return FALSE;
    }
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $nursery
   *
   * @return \Donquixote\ObCK\Optionlessness\OptionlessnessInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromFormula(FormulaInterface $formula, NurseryInterface $nursery): OptionlessnessInterface {
    /** @var \Donquixote\ObCK\Optionlessness\OptionlessnessInterface $candidate */
    $candidate = $nursery->breed($formula, OptionlessnessInterface::class);
    return $candidate;
  }

  /**
   * {@inheritdoc}
   */
  public function isOptionless(): bool {
    return $this->optionless;
  }

}
