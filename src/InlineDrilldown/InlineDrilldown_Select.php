<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlineDrilldown;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromFlatSelect;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_FixedValue;

class InlineDrilldown_Select implements InlineDrilldownInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Select\Formula_SelectInterface
   */
  private Formula_SelectInterface $formula;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface $formula
   *
   * @return self
   */
  public static function createFlat(Formula_FlatSelectInterface $formula): self {
    return new self(
      new Formula_Select_FromFlatSelect($formula));
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $formula
   *
   * @return self
   */
  public static function create(Formula_SelectInterface $formula): self {
    return new self($formula);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $formula
   */
  public function __construct(Formula_SelectInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdFormula(): Formula_IdInterface {
    return $this->formula;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula(string $id): ?FormulaInterface {
    return new Formula_ValueProvider_FixedValue($id);
  }

}
