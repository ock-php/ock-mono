<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition;

class ViewsDisplayCondition_DisplayTypeWhitelist implements ViewsDisplayConditionInterface {

  /**
   * @var true[]
   */
  private $allowedDisplayTypes;

  /**
   * @param string[] $allowedDisplayTypes
   */
  public function __construct(array $allowedDisplayTypes) {
    $this->allowedDisplayTypes = array_fill_keys($allowedDisplayTypes, TRUE);
  }

  /**
   * @param string $id
   * @param array $display
   * @param array|null $default_display
   *
   * @return bool
   */
  public function displayCheckCondition(string $id, array $display, array $default_display = NULL): bool {

    return isset($this->allowedDisplayTypes[$display['display_plugin']]);
  }
}
