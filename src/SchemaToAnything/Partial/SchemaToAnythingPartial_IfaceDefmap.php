<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Defmap\TypeToSchema\TypeToSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class SchemaToAnythingPartial_IfaceDefmap extends SchemaToAnythingPartialBase {

  /**
   * @var \Donquixote\Cf\Defmap\TypeToSchema\TypeToSchemaInterface
   */
  private $typeToSchema;

  /**
   * @param \Donquixote\Cf\Defmap\TypeToSchema\TypeToSchemaInterface $typeToSchema
   */
  public function __construct(TypeToSchemaInterface $typeToSchema) {
    $this->typeToSchema = $typeToSchema;
    parent::__construct(CfSchema_IfaceWithContext::class, NULL);
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  protected function schemaDoGetObject(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ) {

    /** @var \Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext $schema */

    $schema = $this->typeToSchema->typeGetSchema(
      $schema->getInterface(),
      $schema->getContext());

    if (NULL === $schema) {
      return NULL;
    }

    return $helper->schema($schema, $interface);
  }
}
