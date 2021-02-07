<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchemaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContext;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;

class SchemaToAnythingPartial_Iface extends SchemaToAnythingPartialBase {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchemaInterface
   */
  private $typeToSchema;

  /**
   * @param \Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchemaInterface $typeToSchema
   */
  public function __construct(TypeToSchemaInterface $typeToSchema) {
    $this->typeToSchema = $typeToSchema;
    parent::__construct(Formula_IfaceWithContext::class, NULL);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param string $interface
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  protected function schemaDoGetObject(
    FormulaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ) {

    /** @var \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContext $schema */

    $schema = $this->typeToSchema->typeGetSchema(
      $schema->getInterface(),
      $schema->getContext());

    if (NULL === $schema) {
      return NULL;
    }

    return $helper->schema($schema, $interface);
  }
}
