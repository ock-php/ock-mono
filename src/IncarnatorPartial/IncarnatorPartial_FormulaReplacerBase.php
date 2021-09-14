<?php
declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

abstract class IncarnatorPartial_FormulaReplacerBase extends IncarnatorPartialBase {

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
    IncarnatorInterface $incarnator
  ): ?object {
    $replacement = $this->formulaGetReplacement($formula, $incarnator);
    if ($replacement === NULL) {
      return NULL;
    }
    if (get_class($replacement) === get_class($formula)) {
      // Looks like recursion.
      return NULL;
    }
    return Incarnator::getObject(
      $replacement,
      $interface,
      $incarnator);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Original formula.
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   *   Replacement formula.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  abstract protected function formulaGetReplacement(FormulaInterface $formula, IncarnatorInterface $incarnator): ?FormulaInterface;

}
