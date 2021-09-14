<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

class Formula_Group_Empty implements Formula_GroupInterface {

  /**
   * {@inheritdoc}
   */
  public function getItemFormulas(): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {
    return [];
  }
}
