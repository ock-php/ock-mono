<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class DefinitionToSchema_Replacer implements DefinitionToSchemaInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $decorated
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   */
  public function __construct(
    DefinitionToSchemaInterface $decorated,
    SchemaReplacerInterface $replacer
  ) {
    $this->decorated = $decorated;
    $this->replacer = $replacer;
  }

  /**
   * @param array $definition
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function definitionGetSchema(
    array $definition,
    CfContextInterface $context = NULL
  ): CfSchemaInterface {
    $schema = $this->decorated->definitionGetSchema(
      $definition,
      $context);

    if (NULL !== $replacement = $this->replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    return $schema;
  }
}
