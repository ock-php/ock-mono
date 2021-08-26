<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

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
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *   Original formula.
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   *   Replacement formula.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  abstract protected function formulaGetReplacement(FormulaInterface $formula, FormulaToAnythingInterface $helper): ?FormulaInterface;

}
