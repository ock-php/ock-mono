<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Exception\FormulaToAnythingException;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_FormulaReplacer implements FormulaToAnythingPartialInterface {

  /**
   * @var \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   */
  public function __construct(FormulaReplacerInterface $replacer) {
    $this->replacer = $replacer;
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function schema(
    FormulaInterface $schema,
    string $interface,
    FormulaToAnythingInterface $helper
  ): ?object {
    static $recursionLevel = 0;

    if ($recursionLevel > 10) {
      throw new FormulaToAnythingException("Recursion in schema replacer.");
    }

    ++$recursionLevel;
    // Use try/finally to make sure that recursion level will be decremented.
    try {
      $replacement = $this->replacer->schemaGetReplacement($schema);

      if ($replacement === NULL) {
        return NULL;
      }

      if ($replacement === $schema) {
        throw new FormulaToAnythingException("Replacer did not replace. Replacement is identical.");
      }

      if (\get_class($replacement) === \get_class($schema)) {
        throw new FormulaToAnythingException("Replacer did not replace. Replacement has same class.");
      }

      return $helper->schema($replacement, $interface);
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
  public function acceptsFormulaClass(string $schemaClass): bool {
    return $this->replacer->acceptsFormulaClass($schemaClass);
  }
}
