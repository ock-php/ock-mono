<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Group;

class CfSchema_GroupEmpty implements Formula_GroupInterface {

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
