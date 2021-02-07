<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

interface FormulaReplacerPartialInterface {

  /**
   * @return string
   */
  public function getSourceFormulaClass(): string;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  public function schemaGetReplacement(FormulaInterface $schema, FormulaReplacerInterface $replacer): ?FormulaInterface;

}
