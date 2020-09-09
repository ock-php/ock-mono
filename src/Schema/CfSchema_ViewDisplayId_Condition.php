<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;
use Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface;
use Drupal\views\Entity\View;

class CfSchema_ViewDisplayId_Condition implements CfSchema_FlatSelectInterface {

  /**
   * @var \Drupal\views\Entity\View
   */
  private $view;

  /**
   * @var \Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface
   */
  private $condition;

  /**
   * @param \Drupal\views\Entity\View $view
   * @param \Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface $condition
   */
  public function __construct(View $view, ViewsDisplayConditionInterface $condition) {
    $this->view = $view;
    $this->condition = $condition;
  }

  /**
   * @return string[]
   *   Format: $[$optionKey] = $optionLabel
   */
  public function getOptions(): array {

    $displays = $this->view->get('display');

    $defaultDisplay = $displays['default'];

    $options = [];
    foreach ($displays as $display_id => $display) {

      if (!$this->condition->displayCheckCondition(
        $display_id,
        $display,
        $defaultDisplay)
      ) {
        continue;
      }

      $options[$display['id']] = $display['display_title'] . ' (' . $display['display_plugin'] . ')';
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?string {

    $displays = $this->view->get('display');

    if (!isset($displays[$id])) {
      return NULL;
    }

    $display = $displays[$id];

    if (!$this->condition->displayCheckCondition(
      $id,
      $display,
      $displays['default'])
    ) {
      return NULL;
    }

    return $display['display_title'];
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool {

    $displays = $this->view->get('display');

    if (!isset($displays[$id])) {
      return FALSE;
    }

    $display = $displays[$id];

    if (!$this->condition->displayCheckCondition(
      $id,
      $display,
      $displays['default'])
    ) {
      return FALSE;
    }

    return TRUE;
  }
}
