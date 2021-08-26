<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_Null;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Zoo\V2V\Group\V2V_Group_Trivial;
use Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface;
use Donquixote\ObCK\Zoo\V2V\Value\V2V_Value_DrilldownFixedId;
use Donquixote\ObCK\Zoo\V2V\Value\V2V_Value_GroupV2V;

class IdToFormula_InlineExpanded implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface
   */
  private FormulaToAnythingInterface $helper;

  /**
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $decorated
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $helper
   */
  public function __construct(IdToFormulaInterface $decorated, FormulaToAnythingInterface $helper) {
    $this->decorated = $decorated;
    $this->helper = $helper;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula(string $id): ?FormulaInterface {

    if (FALSE === /* $pos = */ strpos($id, '/')) {
      return $this->decorated->idGetFormula($id);
    }

    list($prefix, $suffix) = explode('/', $id, 2);

    if (NULL === $rawNestedFormula = $this->decorated->idGetFormula($prefix)) {
      return NULL;
    }

    try {
      $nestedFormula = Formula::replace($rawNestedFormula, $this->helper);
    }
    catch (FormulaToAnythingException $e) {
      // @todo Log this.
      return NULL;
    }

    if ($nestedFormula instanceof Formula_GroupValInterface) {
      return $this->crackGroupFormula(
        $nestedFormula->getDecorated(),
        $nestedFormula->getV2V(),
        $suffix);
    }

    if ($nestedFormula instanceof Formula_GroupInterface) {
      return $this->crackGroupFormula(
        $nestedFormula,
        new V2V_Group_Trivial(),
        $suffix);
    }

    return $this->crackNestedFormula($nestedFormula, $suffix);
  }

  private function crackGroupFormula(Formula_GroupInterface $group_formula, V2V_GroupInterface $group_v2v, string $suffix): ?FormulaInterface {
    $item_formulas = $group_formula->getItemFormulas();
    if (count($item_formulas) > 1) {
      return NULL;
    }
    $first_item_formula_raw = reset($item_formulas);
    try {
      $first_item_formula = Formula::replace($first_item_formula_raw, $this->helper);
    }
    catch (FormulaToAnythingException $e) {
      // @todo Log this.
      return NULL;
    }
    if ($first_item_formula === NULL) {
      return NULL;
    }
    $first_item_formula_cracked = $this->crackNestedFormula($first_item_formula, $suffix);
    if ($first_item_formula_cracked === NULL) {
      return NULL;
    }
    $first_item_key = key($item_formulas);
    $v2v = new V2V_Value_GroupV2V($group_v2v, $first_item_key);
    return new Formula_ValueToValue($first_item_formula_cracked, $v2v);
  }

  private function crackNestedFormula(FormulaInterface $nestedFormula, string $suffix) {

    if ($nestedFormula instanceof Formula_DecoKeyInterface) {
      $nestedFormula = $nestedFormula->getDecorated();
    }

    if ($nestedFormula instanceof Formula_DrilldownInterface) {
      return $nestedFormula->getIdToFormula()->idGetFormula($suffix);
    }

    if ($nestedFormula instanceof Formula_IdInterface) {
      return new Formula_ValueProvider_Null();
    }

    if ($nestedFormula instanceof Formula_DrilldownValInterface) {
      $deepFormula = $nestedFormula->getDecorated()->getIdToFormula()->idGetFormula($suffix);
      $v2v = new V2V_Value_DrilldownFixedId($nestedFormula->getV2V(), $suffix);
      return new Formula_ValueToValue($deepFormula, $v2v);
    }

    return NULL;
  }

}
