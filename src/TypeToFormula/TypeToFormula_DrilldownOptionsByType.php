<?php
declare(strict_types=1);

namespace Donquixote\ObCK\TypeToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromOptions;
use Donquixote\ObCK\DrilldownOptionsByType\DrilldownOptionsByTypeInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromOptions;

/**
 * This is a version of TypeToFormula* where $type is assumed to be an interface
 * name.
 */
class TypeToFormula_DrilldownOptionsByType implements TypeToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\DrilldownOptionsByType\DrilldownOptionsByTypeInterface
   */
  private $drilldownOptionsByType;

  /**
   * @param \Donquixote\ObCK\DrilldownOptionsByType\DrilldownOptionsByTypeInterface $drilldownOptionsByType
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
