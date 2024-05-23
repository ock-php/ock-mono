<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\views\Entity\View;

/**
 * Formula for values of the structure "$viewId.$viewDisplayId".
 */
class Formula_ViewsDisplay_Id_EntityIdArg extends Formula_ViewsDisplayId {

  /**
   * @param \Drupal\views\Entity\View $view
   * @param string $displayId
   * @param array $display
   *
   * @return bool
   */
  protected function checkDisplay(View $view, string $displayId, array $defaultDisplay, array $display): bool {
    if (!($display['enabled'] ?? TRUE)) {
      return FALSE;
    }

    if (isset($display['display_options']['arguments'])) {
      $arguments = $display['display_options']['arguments'];
    }
    elseif (isset($default_display['display_options']['arguments'])) {
      $arguments = $default_display['display_options']['arguments'];
    }
    else {
      $arguments = [];
    }

    return TRUE;
  }

}
