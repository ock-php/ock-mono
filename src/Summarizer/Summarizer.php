<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   */
  public static function fromSchema(
    FormulaInterface $schema,
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
