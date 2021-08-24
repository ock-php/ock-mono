<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Option\DrilldownOptionInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;

class IdToFormula_FromOptions implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Drilldown\Option\DrilldownOptionInterface[]
   */
  private $options;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Drilldown\Option\DrilldownOptionInterface[] $options
   */
  public function __construct(array $options) {
    self::validateOptions(...array_values($options));
    $this->options = $options;
  }

  /**
   * @param \Donquixote\ObCK\Formula\Drilldown\Option\DrilldownOptionInterface ...$options
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
