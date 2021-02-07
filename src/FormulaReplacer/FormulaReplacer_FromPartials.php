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
   * @var array
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
  public function schemaGetReplacement(FormulaInterface $schema): ?FormulaInterface {

    foreach ($this->schemaClassGetPartials(
      \get_class($schema)
    ) as $partial) {
      if (NULL !== $replacement = $partial->schemaGetReplacement($schema, $this)) {
        return $replacement;
      }
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $schemaClass): bool {
    return [] !== $this->schemaClassGetPartials($schemaClass);
  }

  /**
   * @param string $schemaClass
   *
   * @return \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface[]
   */
  private function schemaClassGetPartials(string $schemaClass): array {
    return $this->partialss[$schemaClass]
      ?? $this->partialss[$schemaClass] = $this->schemaClassFindPartials($schemaClass);
  }

  /**
   * @param string $schemaClass
   *
   * @return \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface[]
   */
  private function schemaClassFindPartials(string $schemaClass): array {

    $partials = [];
    foreach ($this->partials as $partial) {
      $acceptedFormulaClass = $partial->getSourceFormulaClass();
      if (is_a($schemaClass, $acceptedFormulaClass, TRUE)) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }
}
