<?php

namespace Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition;

class ViewsDisplayCondition_And implements ViewsDisplayConditionInterface {

  /**
   * @var \Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface[]
   */
  private $filters;

  /**
   * @param \Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface[] $filters
   */
  public function __construct(array $filters) {
    $this->filters = $filters;
  }

  /**
   * @param string $id
   * @param array $display
   * @param array|null $default_display
   *
   * @return bool
   */
  public function displayCheckCondition($id, array $display, array $default_display = NULL) {

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
