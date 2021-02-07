<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Neutral\Formula_NeutralInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer_Neutral extends UtilBase {


  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Neutral\Formula_NeutralInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(Formula_NeutralInterface $schema, SchemaToAnythingInterface $schemaToAnything): SummarizerInterface {
    return Summarizer::fromSchema($schema->getDecorated(), $schemaToAnything);
  }
}
