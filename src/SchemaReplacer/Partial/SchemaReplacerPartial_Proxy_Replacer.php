<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Proxy\Replacer\Formula_Proxy_ReplacerInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_Proxy_Replacer implements SchemaReplacerPartialInterface {

  /**
   * {@inheritdoc}
   */
  public function getSourceSchemaClass(): string {
    return Formula_Proxy_ReplacerInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(FormulaInterface $proxy, SchemaReplacerInterface $replacer): ?FormulaInterface {

    if (!$proxy instanceof Formula_Proxy_ReplacerInterface) {
      return NULL;
    }

    return $proxy->replacerGetSchema($replacer);
  }
}
