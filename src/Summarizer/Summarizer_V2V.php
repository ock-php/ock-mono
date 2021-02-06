<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\SchemaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\SchemaBase\Formula_ValueToValueBaseInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(
    Formula_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?SummarizerInterface {
    return Summarizer::fromSchema(
      $schema->getDecorated(),
      $schemaToAnything
    );
  }

}
