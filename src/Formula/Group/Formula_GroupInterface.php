<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Group;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface Formula_GroupInterface extends FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   *   Format: $[$groupItemKey] = $groupItemSchema
   */
  public function getItemSchemas(): array;

  /**
   * @return \Donquixote\OCUI\Text\TextInterface[]
   */
  public function getLabels(): array;

}
