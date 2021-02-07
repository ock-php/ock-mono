<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Neutral\Formula_NeutralInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer_Neutral extends UtilBase {


  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Neutral\Formula_NeutralInterface $schema
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_NeutralInterface $schema, FormulaToAnythingInterface $schemaToAnything): SummarizerInterface {
    return Summarizer::fromFormula($schema->getDecorated(), $schemaToAnything);
  }
}
