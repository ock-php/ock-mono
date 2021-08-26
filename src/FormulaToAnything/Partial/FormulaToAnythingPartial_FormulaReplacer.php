<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_FormulaReplacer extends FormulaToAnythingPartialZeroBase {

  /**
   * @var \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $replacer
   */
  public function __construct(FormulaReplacerInterface $replacer) {
    $this->replacer = $replacer;
  }

  /**
   * {@inheritdoc}
   */
  public function formula(
    FormulaInterface $formula,
    string $interface,
    FormulaToAnythingInterface $helper
  ): ?object {
    static $recursionLevel = 0;

    if ($recursionLevel > 10) {
      throw new FormulaToAnythingException("Recursion in formula replacer.");
    }

    ++$recursionLevel;
    // Use try/finally to make sure that recursion level will be decremented.
    try {
      $replacement = $this->replacer->formulaGetReplacement($formula);

      if ($replacement === NULL) {
        return NULL;
      }

      if ($replacement === $formula) {
        throw new FormulaToAnythingException("Replacer did not replace. Replacement is identical.");
      }

      if (\get_class($replacement) === \get_class($formula)) {
        throw new FormulaToAnythingException("Replacer did not replace. Replacement has same class.");
      }

      return $helper->formula($replacement, $interface);
    }
    finally {
      --$recursionLevel;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $formulaClass): bool {
    return $this->replacer->acceptsFormulaClass($formulaClass);
  }
}
