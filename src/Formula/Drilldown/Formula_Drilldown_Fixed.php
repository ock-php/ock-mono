<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Drilldown;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Id\Formula_IdInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_Fixed;
use Donquixote\Ock\IdToFormula\IdToFormula_Fixed;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Donquixote\Ock\Text\TextInterface;

class Formula_Drilldown_Fixed extends Formula_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\Ock\Formula\Select\Formula_Select_Fixed
   */
  private $idFormula;

  /**
   * @var \Donquixote\Ock\IdToFormula\IdToFormula_Fixed
   */
  private $idToFormula;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface[] $formulas
   * @param \Donquixote\Ock\Text\TextInterface[] $labels
   * @param bool $orNull
   *
   * @return self
   */
  public static function create(array $formulas = [], array $labels = [], $orNull = FALSE): Formula_Drilldown_Fixed {
    return new self(
      Formula_Select_Fixed::createFlat($labels),
      new IdToFormula_Fixed($formulas),
      $orNull);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Select\Formula_Select_Fixed $idFormula
   * @param \Donquixote\Ock\IdToFormula\IdToFormula_Fixed $idToFormula
   * @param bool $orNull
   */
  private function __construct(Formula_Select_Fixed $idFormula, IdToFormula_Fixed $idToFormula, $orNull = FALSE) {
    $this->idFormula = $idFormula;
    $this->idToFormula = $idToFormula;
    parent::__construct($orNull);
  }

  /**
   * @param string $id
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Text\TextInterface $label
   * @param string|null $group_id
   * @param \Donquixote\Ock\Text\TextInterface|null $group_label
   *
   * @return static
   */
  public function withOption(string $id, FormulaInterface $formula, TextInterface $label, string $group_id = NULL, TextInterface $group_label = NULL): Formula_Drilldown_Fixed {
    $clone = clone $this;
    $clone->idFormula = $this->idFormula->withOption($id, $label, $group_id);
    $clone->idToFormula = $this->idToFormula->withFormula($id, $formula);
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdFormula(): Formula_IdInterface {
    return $this->idFormula;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdToFormula(): IdToFormulaInterface {
    return $this->idToFormula;
  }

}
