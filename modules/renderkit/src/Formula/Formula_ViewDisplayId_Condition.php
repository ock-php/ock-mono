<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Ock\Ock\Text\TextInterface;
use Drupal\ock\DrupalText;
use Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface;
use Drupal\views\Entity\View;

class Formula_ViewDisplayId_Condition implements Formula_FlatSelectInterface {

  /**
   * @param \Drupal\views\Entity\View $view
   * @param \Drupal\renderkit\Formula\Misc\ViewsDisplayCondition\ViewsDisplayConditionInterface $condition
   */
  public function __construct(
    private readonly View $view,
    private readonly ViewsDisplayConditionInterface $condition,
  ) {}

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
  public function idGetLabel($id): ?TextInterface {

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

    return DrupalText::fromVar($display['display_title']);
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {

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
