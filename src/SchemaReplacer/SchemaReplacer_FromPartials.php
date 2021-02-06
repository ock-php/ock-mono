<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class SchemaReplacer_FromPartials implements SchemaReplacerInterface {

  /**
   * @var \Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
   */
  private $partials;

  /**
   * @var array
   */
  private $partialss = [];

  /**
   * @param \Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartialInterface[] $partials
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
  public function acceptsSchemaClass(string $schemaClass): bool {
    return [] !== $this->schemaClassGetPartials($schemaClass);
  }

  /**
   * @param string $schemaClass
   *
   * @return \Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
   */
  private function schemaClassGetPartials(string $schemaClass): array {
    return $this->partialss[$schemaClass]
      ?? $this->partialss[$schemaClass] = $this->schemaClassFindPartials($schemaClass);
  }

  /**
   * @param string $schemaClass
   *
   * @return \Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
   */
  private function schemaClassFindPartials(string $schemaClass): array {

    $partials = [];
    foreach ($this->partials as $partial) {
      $acceptedSchemaClass = $partial->getSourceSchemaClass();
      if (is_a($schemaClass, $acceptedSchemaClass, TRUE)) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }
}
