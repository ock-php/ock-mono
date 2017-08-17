<?php

namespace Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition;

class ViewsDisplayCondition_NoArgs implements ViewsDisplayConditionInterface {

  /**
   * @param string $id
   * @param array $display
   * @param array|null $default_display
   *
   * @return bool
   */
  public function displayCheckCondition($id, array $display, array $default_display = NULL) {

    if (isset($display['display_options']['arguments'])) {
      $arguments = $display['display_options']['arguments'];
    }
    elseif (isset($default_display['display_options']['arguments'])) {
      $arguments = $default_display['display_options']['arguments'];
    }
    else {
      $arguments = [];
    }

    return [] === $arguments;
  }
}
