<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\ViewsDisplayCondition;

interface ViewsDisplayConditionInterface {

  /**
   * @param string $id
   * @param array $display
   * @param array|null $default_display
   *
   * @return bool
   */
  public function displayCheckCondition(string $id, array $display, array $default_display = NULL): bool;

}
