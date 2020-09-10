<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\UtilBase;

final class Summarizer_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?SummarizerInterface {
    return Summarizer::fromSchema(
      $schema->getDecorated(),
      $schemaToAnything
    );
  }

}
