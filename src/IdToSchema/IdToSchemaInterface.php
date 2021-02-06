<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface IdToSchemaInterface {

  /**
   * @param string $id
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  public function idGetSchema(string $id): ?FormulaInterface;

}
