<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

class DefinitionToSchema_Replacer implements DefinitionToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $decorated
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
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
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\OCUI\Exception\CfSchemaCreationException
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
