<?php
declare(strict_types=1);

namespace Donquixote\Ock\Optionlessness;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\FormulaToAnythingException;
use Donquixote\Ock\Nursery\NurseryInterface;

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
   * @param \Donquixote\Ock\Nursery\NurseryInterface $nursery
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
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $nursery
   *
   * @return \Donquixote\Ock\Optionlessness\OptionlessnessInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public static function fromFormula(FormulaInterface $formula, NurseryInterface $nursery): OptionlessnessInterface {
    /** @var \Donquixote\Ock\Optionlessness\OptionlessnessInterface $candidate */
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
