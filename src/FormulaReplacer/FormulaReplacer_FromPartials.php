<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class FormulaReplacer_FromPartials implements FormulaReplacerInterface {

  /**
   * @var \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface[]
   */
  private $partials;

  /**
   * @var \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface[][]
   *   Format: $[$formulaClass][] = $partial.
   */
  private $partialss = [];

  /**
   * @param \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $formula): ?FormulaInterface {

    foreach ($this->formulaClassGetPartials(
      \get_class($formula)
    ) as $partial) {
      if (NULL !== $replacement = $partial->formulaGetReplacement($formula, $this)) {
        return $replacement;
      }
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $formulaClass): bool {
    return [] !== $this->formulaClassGetPartials($formulaClass);
  }

  /**
   * @param string $formulaClass
   *
   * @return \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface[]
   */
  private function formulaClassGetPartials(string $formulaClass): array {
    return $this->partialss[$formulaClass]
      ?? $this->partialss[$formulaClass] = $this->formulaClassFindPartials($formulaClass);
  }

  /**
   * @param string $formulaClass
   *
   * @return \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface[]
   */
  private function formulaClassFindPartials(string $formulaClass): array {

    $partials = [];
    foreach ($this->partials as $partial) {
      $acceptedFormulaClass = $partial->getSourceFormulaClass();
      if (is_a($formulaClass, $acceptedFormulaClass, TRUE)) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }
}
