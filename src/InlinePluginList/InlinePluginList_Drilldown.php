<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlinePluginList;

use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\ObCK\V2V\Drilldown\V2V_DrilldownInterface;
use Donquixote\ObCK\V2V\Value\V2V_Value_DrilldownFixedId;

class InlinePluginList_Drilldown implements InlinePluginListInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Select\Formula_SelectInterface
   */
  private Formula_SelectInterface $idFormula;

  /**
   * @var \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  private Formula_DrilldownInterface $formula;

  /**
   * @var \Donquixote\ObCK\V2V\Drilldown\V2V_DrilldownInterface
   */
  private V2V_DrilldownInterface $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownValInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromDrilldownVal(
    Formula_DrilldownValInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?self {
    return self::create(
      $formula->getDecorated(),
      $formula->getV2V(),
      $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromDrilldown(
    Formula_DrilldownInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?self {
    return self::create(
      $formula,
      new V2V_Drilldown_Trivial(),
      $formulaToAnything);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\ObCK\V2V\Drilldown\V2V_DrilldownInterface $v2v
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return static|null
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_DrilldownInterface $formula,
    V2V_DrilldownInterface $v2v,
    FormulaToAnythingInterface $formulaToAnything
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
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $idFormula
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\ObCK\V2V\Drilldown\V2V_DrilldownInterface $v2v
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
  public function getIds(): array {
    $options = $this->idFormula->getOptions(NULL);
    foreach ($this->idFormula->getOptGroups() as $group_id => $groupLabel) {
      $options += $this->idFormula->getOptions($group_id);
    }
    return array_keys($options);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetPlugin(string $id): ?Plugin {
    $formula = $this->formula->getIdToFormula()->idGetFormula($id);
    if ($formula === NULL) {
      return NULL;
    }
    if ($this->v2v) {
      $formula = new Formula_ValueToValue(
        $formula,
        new V2V_Value_DrilldownFixedId($this->v2v, $id));
    }
    return new Plugin(
      $this->idFormula->idGetLabel($id) ?? Text::s($id),
      NULL,
      $formula,
      []);
  }

}
