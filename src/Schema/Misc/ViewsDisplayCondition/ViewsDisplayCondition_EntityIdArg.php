<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition;

class ViewsDisplayCondition_EntityIdArg implements ViewsDisplayConditionInterface {

  /**
   * @var null|string
   */
  private $entityTypeId;

  /**
   * @param string|null $entityTypeId
   */
  public function __construct($entityTypeId = NULL) {
    $this->entityTypeId = $entityTypeId;
  }

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

    if ([] === $arguments) {
      return FALSE;
    }

    $arg = array_shift($arguments);

    if ([] !== $arguments) {
      return FALSE;
    }

    if (!isset($arg['validate']['type'])) {
      return FALSE;
    }

    $argType = $arg['validate']['type'];

    if (NULL !== $this->entityTypeId) {
      return 'entity:' . $this->entityTypeId === $argType;
    }

    if (0 === strncmp($argType, 'entity:', 7)) {
      return TRUE;
    }

    return FALSE;
  }
}
