<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\IdToFormula\IdToFormula_FromOptions;
use Donquixote\OCUI\Formula\Select\Formula_Select_FromOptions;
use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;

class Formula_Drilldown extends Formula_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\OCUI\Formula\Id\Formula_IdInterface
   */
  private $idFormula;

  /**
   * @var \Donquixote\OCUI\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $idFormula
   * @param \Donquixote\OCUI\IdToFormula\IdToFormulaInterface $idToFormula
   * @param bool $orNull
   *
   * @return self
   */
  public static function create(Formula_IdInterface $idFormula, IdToFormulaInterface $idToFormula, $orNull = FALSE): self {
    return new self($idFormula, $idToFormula, $orNull);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[] $options
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
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $idFormula
   * @param \Donquixote\OCUI\IdToFormula\IdToFormulaInterface $idToFormula
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
