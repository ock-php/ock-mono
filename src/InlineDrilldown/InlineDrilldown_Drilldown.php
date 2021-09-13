<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Id\Formula_IdInterface;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface;
use Donquixote\Ock\V2V\Value\V2V_Value_DrilldownFixedId;

class InlineDrilldown_Drilldown implements InlineDrilldownInterface {

  /**
   * @var \Donquixote\Ock\Formula\Select\Formula_SelectInterface
   */
  private Formula_SelectInterface $idFormula;

  /**
   * @var \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   */
  private Formula_DrilldownInterface $formula;

  /**
   * @var \Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface
   */
  private V2V_DrilldownInterface $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromDrilldownVal(
    Formula_DrilldownValInterface $formula,
    NurseryInterface $formulaToAnything
  ): ?self {
    return self::create(
      $formula->getDecorated(),
      $formula->getV2V(),
      $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromDrilldown(
    Formula_DrilldownInterface $formula,
    NurseryInterface $formulaToAnything
  ): ?self {
    return self::create(
      $formula,
      new V2V_Drilldown_Trivial(),
      $formulaToAnything);
  }

  /**
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface $v2v
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return static|null
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(
    Formula_DrilldownInterface $formula,
    V2V_DrilldownInterface $v2v,
    NurseryInterface $formulaToAnything
  ): ?self {
    $idFormula = Formula::replace(
      $formula->getIdFormula(),
      $formulaToAnything);
    if (!$idFormula instanceof Formula_SelectInterface) {
      return NULL;
    }
    return new self(
      $idFormula,
      $formula,
      $v2v);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Select\Formula_SelectInterface $idFormula
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface $v2v
   */
  public function __construct(
    Formula_SelectInterface $idFormula,
    Formula_DrilldownInterface $formula,
    V2V_DrilldownInterface $v2v
  ) {
    $this->idFormula = $idFormula;
    $this->formula = $formula;
    $this->v2v = $v2v;
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
  public function idGetFormula(string $id): ?FormulaInterface {
    $formula = $this->formula->getIdToFormula()->idGetFormula($id);
    if ($formula === NULL) {
      return NULL;
    }
    if ($this->v2v) {
      $formula = new Formula_ValueToValue(
        $formula,
        new V2V_Value_DrilldownFixedId($this->v2v, $id));
    }
    return $formula;
  }

}
