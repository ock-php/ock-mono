<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\UtilBase;

final class Summarizer extends UtilBase {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface|null
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
