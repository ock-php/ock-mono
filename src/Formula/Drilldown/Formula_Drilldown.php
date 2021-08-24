<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Drilldown;

use Donquixote\ObCK\IdToFormula\IdToFormula_FromOptions;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromOptions;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;

class Formula_Drilldown extends Formula_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\ObCK\Formula\Id\Formula_IdInterface
   */
  private $idFormula;

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @param \Donquixote\ObCK\Formula\Id\Formula_IdInterface $idFormula
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $idToFormula
   * @param bool $orNull
   *
   * @return self
   */
  public static function create(Formula_IdInterface $idFormula, IdToFormulaInterface $idToFormula, $orNull = FALSE): self {
    return new self($idFormula, $idToFormula, $orNull);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Drilldown\Option\DrilldownOptionInterface[] $options
   * @param bool $orNull
   *
   * @return self
   */
  public static function fromOptions(array $options, $orNull = FALSE): self {
    return new self(
      new Formula_Select_FromOptions($options),
      new IdToFormula_FromOptions($options),
      $orNull);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Id\Formula_IdInterface $idFormula
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $idToFormula
   * @param bool $orNull
   */
  public function __construct(Formula_IdInterface $idFormula, IdToFormulaInterface $idToFormula, $orNull = FALSE) {
    $this->idFormula = $idFormula;
    $this->idToFormula = $idToFormula;
    parent::__construct($orNull);
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
