<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\Formula\Select\Formula_Select_Fixed;
use Donquixote\OCUI\IdToFormula\IdToFormula_Fixed;
use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;
use Donquixote\OCUI\Text\TextInterface;

class Formula_Drilldown_Fixed extends Formula_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\OCUI\Formula\Select\Formula_Select_Fixed
   */
  private $idFormula;

  /**
   * @var \Donquixote\OCUI\IdToFormula\IdToFormula_Fixed
   */
  private $idToFormula;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $formulas
   * @param string[] $labels
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
   * @param \Donquixote\OCUI\Formula\Select\Formula_Select_Fixed $idFormula
   * @param \Donquixote\OCUI\IdToFormula\IdToFormula_Fixed $idToFormula
   * @param bool $orNull
   */
  private function __construct(Formula_Select_Fixed $idFormula, IdToFormula_Fixed $idToFormula, $orNull = FALSE) {
    $this->idFormula = $idFormula;
    $this->idToFormula = $idToFormula;
    parent::__construct($orNull);
  }

  /**
   * @param string $id
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\Text\TextInterface $label
   * @param ?string $group_id
   * @param ?\Donquixote\OCUI\Text\TextInterface $group_label
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
