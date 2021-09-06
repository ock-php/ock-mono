<?php
declare(strict_types=1);

namespace Drupal\renderkit\Schema\Misc\ViewsDisplayCondition;

class ViewsDisplayCondition_Status implements ViewsDisplayConditionInterface {

  /**
   * @var bool
   */
  private $status;

  /**
   * @param bool $status
   */
  public function __construct($status = TRUE) {
    $this->status = $status;
  }

  /**
   * @param string $id
   * @param array $display
   * @param array|null $default_display
   *
   * @return bool
   */
  public function displayCheckCondition(string $id, array $display, array $default_display = NULL): bool {

    if (!isset($display['enabled'])) {
      return TRUE;
    }

    return $display['enabled'] !== $this->status;
  }
}
