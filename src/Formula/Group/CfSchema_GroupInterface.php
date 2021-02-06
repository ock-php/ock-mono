<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Group;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

interface CfSchema_GroupInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface[]
   *   Format: $[$groupItemKey] = $groupItemSchema
   */
  public function getItemSchemas(): array;

  /**
   * @return \Donquixote\OCUI\Text\TextInterface[]
   */
  public function getLabels(): array;

}
