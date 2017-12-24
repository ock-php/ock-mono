<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition;

class ViewsDisplayCondition_AcceptAll implements ViewsDisplayConditionInterface {

  /**
   * @param string $id
   * @param array $display
   * @param array|null $default_display
   *
   * @return bool
   */
  public function displayCheckCondition($id, array $display, array $default_display = NULL) {
    return TRUE;
  }
}
