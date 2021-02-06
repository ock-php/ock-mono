<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Group;

class CfSchema_GroupEmpty implements CfSchema_GroupInterface {

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
