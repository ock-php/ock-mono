<?php

namespace Drupal\renderkit\EnumMap;

use Drupal\cfrapi\EnumMap\EnumMapInterface;

class EnumMap_ViewsDisplayId_Entity implements EnumMapInterface {

  /**
   * @var string|null
   */
  private $entityType;

  /**
   * @param string $entityType
   */
  public function __construct($entityType = NULL) {
    $this->entityType = $entityType;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    list($view_name, $display_id) = explode(':', $id . ':');
    return 1
      && '' !== $view_name
      && '' !== $display_id
      && NULL !== ($view = \views_get_view($view_name))
      && !empty($view->display[$display_id]);
  }

  /**
   * @return mixed[]
   */
  public function getSelectOptions() {

    /** @var \view[] $views */
    $views = \views_get_all_views();

    $options = [];

    foreach ($views as $view_id => $view) {
      if (!empty($view->disabled)) {
        continue;
      }

      if (empty($view->display)) {
        // Skip this view as it is broken.
        continue;
      }

      $view_label = $view->get_human_name();

      /** @var \views_display $display */
      foreach ($view->display as $display_id => $display) {
        /** @noinspection PhpUndefinedFieldInspection */
        if ('default' === $display->display_plugin || 'page' === $display->display_plugin) {
          continue;
        }
        if (empty($display->display_options['argument_input'])) {
          continue;
        }
        if (count($display->display_options['argument_input']) > 1) {
          continue;
        }

        // Pick the first and only argument.
        foreach ($display->display_options['argument_input'] as $arg_name => $arg_options) {}

        /** @noinspection DisconnectedForeachInstructionInspection */
        if (!isset($arg_options['context'])) {
          continue;
        }

        $pattern = DRUPAL_PHP_FUNCTION_PATTERN;
        $regex = "@^entity:($pattern)\.($pattern)$@";

        if (!preg_match($regex, $arg_options['context'], $m)) {
          continue;
        }

        /** @noinspection PhpUnusedLocalVariableInspection */
        list(, $entity_type, $id_key) = $m;

        if (NULL !== $this->entityType && $entity_type !== $this->entityType) {
          // Not the type we are looking for.
          continue;
        }

        /** @noinspection PhpUndefinedFieldInspection */
        $options[$view_label][$view_id . ':' . $display_id] = $display->display_title;
      }
    }

    return $options;
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    list($view_name, $display_id) = explode(':', $id . ':');
    if ('' === $view_name || '' === $display_id) {
      return NULL;
    }
    $view = \views_get_view($view_name);
    if (NULL === $view) {
      return NULL;
    }
    if (empty($view->display[$display_id])) {
      return NULL;
    }
    $display = $view->display[$display_id];
    $display_label = !empty($display->display_title)
      ? $display->display_title
      : $display_id;
    return $view->get_human_name() . ': ' . $display_label;
  }
}
