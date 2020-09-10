<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\UtilBase;

final class Summarizer_Neutral extends UtilBase {


  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_NeutralInterface $schema, SchemaToAnythingInterface $schemaToAnything): SummarizerInterface {
    return Summarizer::fromSchema($schema->getDecorated(), $schemaToAnything);
  }
}
