<?php
declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

abstract class Incarnator_FormulaReplacerBase extends IncarnatorPartialBase {

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
    IncarnatorInterface $helper
  ): ?object {
    $replacement = $this->formulaGetReplacement($formula, $helper);
    if ($replacement === NULL) {
      return NULL;
    }
    if (get_class($replacement) === get_class($formula)) {
      // Looks like recursion.
      return NULL;
    }
    return $helper->incarnate($replacement, $interface);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Original formula.
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $nursery
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   *   Replacement formula.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  abstract protected function formulaGetReplacement(FormulaInterface $formula, IncarnatorInterface $nursery): ?FormulaInterface;

}
