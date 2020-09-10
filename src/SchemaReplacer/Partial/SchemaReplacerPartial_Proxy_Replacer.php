<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Proxy\Replacer\CfSchema_Proxy_ReplacerInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_Proxy_Replacer implements SchemaReplacerPartialInterface {

  /**
   * {@inheritdoc}
   */
  public function getSourceSchemaClass(): string {
    return CfSchema_Proxy_ReplacerInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(CfSchemaInterface $proxy, SchemaReplacerInterface $replacer): ?CfSchemaInterface {

    if (!$proxy instanceof CfSchema_Proxy_ReplacerInterface) {
      return NULL;
    }

    return $proxy->replacerGetSchema($replacer);
  }
}
