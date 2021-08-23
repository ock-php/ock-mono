<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

abstract class FormulaToAnythingPartial_FormulaReplacerBase extends FormulaToAnythingPartialBase {

  /**
   * Constructor.
   *
   * @param string $formulaType
   */
  public function __construct(string $formulaType) {
    parent::__construct($formulaType, NULL);
  }

  /**
   * {@inheritdoc}
   */
  final protected function formulaDoGetObject(
    FormulaInterface $formula,
    string $interface,
    FormulaToAnythingInterface $helper
  ): ?object {
    $replacement = $this->formulaGetReplacement($formula, $helper);
    if ($replacement === NULL) {
      return NULL;
    }
    if (get_class($replacement) === get_class($formula)) {
      // Looks like recursion.
      return NULL;
    }
    return $helper->formula($replacement, $interface);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   *   Original formula.
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   *   Replacement formula.
   */
  abstract protected function formulaGetReplacement(FormulaInterface $formula, FormulaToAnythingInterface $helper): ?FormulaInterface;

}
