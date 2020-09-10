<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaReplacer;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class SchemaReplacer_FromPartials implements SchemaReplacerInterface {

  /**
   * @var \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
   */
  private $partials;

  /**
   * @var array
   */
  private $partialss = [];

  /**
   * @param \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(CfSchemaInterface $schema): ?CfSchemaInterface {

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
  public function acceptsSchemaClass($schemaClass): bool {
    return [] !== $this->schemaClassGetPartials($schemaClass);
  }

  /**
   * @param string $schemaClass
   *
   * @return \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
   */
  private function schemaClassGetPartials($schemaClass): array {
    return $this->partialss[$schemaClass]
      ?? $this->partialss[$schemaClass] = $this->schemaClassFindPartials($schemaClass);
  }

  /**
   * @param string $schemaClass
   *
   * @return \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
   */
  private function schemaClassFindPartials($schemaClass): array {

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
