<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Proxy\Replacer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

interface Formula_Proxy_ReplacerInterface extends FormulaInterface {

  /**
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function replacerGetSchema(SchemaReplacerInterface $replacer): FormulaInterface;

}
