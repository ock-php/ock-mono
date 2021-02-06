<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   */
  public static function fromSchema(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?SummarizerInterface {

    $candidate = $schemaToAnything->schema(
      $schema,
      SummarizerInterface::class);

    if (!$candidate instanceof SummarizerInterface) {
      return NULL;
    }

    return $candidate;
  }

}
