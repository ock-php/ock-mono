<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface;
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
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface $ifaceSchema
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  protected function schemaDoGetReplacement(
    Formula_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  ): ?FormulaInterface {

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
