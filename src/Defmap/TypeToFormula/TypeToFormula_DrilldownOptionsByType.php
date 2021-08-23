<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\IdToFormula\IdToFormula_FromOptions;
use Donquixote\OCUI\DrilldownOptionsByType\DrilldownOptionsByTypeInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_Drilldown;
use Donquixote\OCUI\Formula\Select\Formula_Select_FromOptions;

/**
 * This is a version of TypeToFormula* where $type is assumed to be an interface
 * name.
 */
class TypeToFormula_DrilldownOptionsByType implements TypeToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\DrilldownOptionsByType\DrilldownOptionsByTypeInterface
   */
  private $drilldownOptionsByType;

  /**
   * @param \Donquixote\OCUI\DrilldownOptionsByType\DrilldownOptionsByTypeInterface $drilldownOptionsByType
   */
  public function __construct(DrilldownOptionsByTypeInterface $drilldownOptionsByType) {
    $this->drilldownOptionsByType = $drilldownOptionsByType;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetFormula(string $type, bool $or_null): FormulaInterface {
    $options_by_type = $this->drilldownOptionsByType->getDrilldownOptionsByType();
    $options = $options_by_type[$type] ?? [];
    return new Formula_Drilldown(
      new Formula_Select_FromOptions($options),
      new IdToFormula_FromOptions($options));
  }

}
