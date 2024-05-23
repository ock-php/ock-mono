<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\ViewsDisplayCondition;

class ViewsDisplayCondition_And implements ViewsDisplayConditionInterface {

  /**
   * @param \Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface[] $filters
   */
  public function __construct(
    private readonly array $filters,
  ) {}

  /**
   * @param string $id
   * @param array $display
   * @param array|null $default_display
   *
   * @return bool
   */
  public function displayCheckCondition(string $id, array $display, array $default_display = NULL): bool {

    foreach ($this->filters as $filter) {
      if (!$filter->displayCheckCondition(
        $id,
        $display,
        $default_display)
      ) {
        return FALSE;
      }
    }

    return TRUE;
  }
}
