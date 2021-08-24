<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_Null;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\ObCK\Zoo\V2V\Value\V2V_Value_DrilldownFixedId;

class IdToFormula_InlineExpanded implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $decorated
   */
  public function __construct(IdToFormulaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($combinedId): ?FormulaInterface {

    if (FALSE === /* $pos = */ strpos($combinedId, '/')) {
      return $this->decorated->idGetFormula($combinedId);
    }

    list($prefix, $suffix) = explode('/', $combinedId, 2);

    if (NULL === $nestedFormula = $this->decorated->idGetFormula($prefix)) {
      return NULL;
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
