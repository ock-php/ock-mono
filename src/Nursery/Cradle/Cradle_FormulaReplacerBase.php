<?php
declare(strict_types=1);

namespace Donquixote\Ock\Nursery\Cradle;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Nursery\NurseryInterface;

abstract class Cradle_FormulaReplacerBase extends CradleBase {

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
    NurseryInterface $helper
  ): ?object {
    $replacement = $this->formulaGetReplacement($formula, $helper);
    if ($replacement === NULL) {
      return NULL;
    }
    if (get_class($replacement) === get_class($formula)) {
      // Looks like recursion.
      return NULL;
    }
    return $helper->breed($replacement, $interface);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Original formula.
   * @param \Donquixote\Ock\Nursery\NurseryInterface $nursery
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   *   Replacement formula.
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  abstract protected function formulaGetReplacement(FormulaInterface $formula, NurseryInterface $nursery): ?FormulaInterface;

}
