<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface;
use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;

class IdToFormula_FromOptions implements IdToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[]
   */
  private $options;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[] $options
   */
  public function __construct(array $options) {
    self::validateOptions(...array_values($options));
    $this->options = $options;
  }

  /**
   * @param \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface ...$options
   */
  private static function validateOptions(DrilldownOptionInterface ...$options): void {}

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {

    if (!isset($this->options[$id])) {
      return NULL;
    }

    return $this->options[$id]->getFormula();
  }
}
