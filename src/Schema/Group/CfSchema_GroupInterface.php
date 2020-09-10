<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Group;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface CfSchema_GroupInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface[]
   *   Format: $[$groupItemKey] = $groupItemSchema
   */
  public function getItemSchemas(): array;

  /**
   * @return string[]
   */
  public function getLabels(): array;

}
