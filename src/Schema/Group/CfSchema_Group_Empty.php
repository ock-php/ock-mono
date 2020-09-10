<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Group;

class CfSchema_Group_Empty implements CfSchema_GroupInterface {

  /**
   * {@inheritdoc}
   */
  public function getItemSchemas(): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {
    return [];
  }
}
