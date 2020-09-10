<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_IfaceTag extends SchemaReplacerPartial_IfaceBase {

  /**
   * @var \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface $decorated
   */
  public function __construct(SchemaReplacerPartialInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param \Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface $ifaceSchema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  protected function schemaDoGetReplacement(
    CfSchema_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  ): CfSchemaInterface {

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
