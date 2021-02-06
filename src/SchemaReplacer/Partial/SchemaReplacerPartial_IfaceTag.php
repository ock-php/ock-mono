<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Formula\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\OCUI\Formula\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_IfaceTag extends SchemaReplacerPartial_IfaceBase {

  /**
   * @var \Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartialInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartialInterface $decorated
   */
  public function __construct(SchemaReplacerPartialInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param \Donquixote\OCUI\Formula\Iface\CfSchema_IfaceWithContextInterface $ifaceSchema
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  protected function schemaDoGetReplacement(
    CfSchema_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  ): ?CfSchemaInterface {

    if (NULL === $schema = $this->decorated->schemaGetReplacement($ifaceSchema, $replacer)) {
      // @todo Tag this one as well?
      return NULL;
      # $schema = $ifaceSchema;
    }

    $schema = new CfSchema_Neutral_IfaceTransformed(
      $schema,
      $ifaceSchema->getInterface(),
      $ifaceSchema->getContext());

    return $schema;
  }
}
