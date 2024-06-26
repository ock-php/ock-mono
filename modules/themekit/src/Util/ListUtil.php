<?php
declare(strict_types=1);

namespace Drupal\themekit\Util;

use Drupal\Core\Render\Element;
use Drupal\Core\Render\RendererInterface;

final class ListUtil {

  /**
   * @param array $element
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *
   * @return array
   * @throws \Exception
   *   Exception from renderer.
   */
  public static function elementRenderListItems(array &$element, RendererInterface $renderer): array {

    $items_rendered = [];
    foreach (self::elementGetListItems($element) as $delta => $item) {

      $item_rendered = $renderer->render($item);

      if ('' === (string)$item_rendered) {
        continue;
      }

      $items_rendered[$delta] = $item_rendered;
    }

    return $items_rendered;
  }

  /**
   * @param array $element
   *
   * @return array[]
   */
  public static function elementGetListItems(array &$element): array {

    if (isset($element['#items'])) {
      return $element['#items'];
    }

    $deltas = Element::children($element);

    $items = [];
    foreach ($deltas as $delta) {
      $items[$delta] = $element[$delta];
      unset($element[$delta]);
    }

    return $items;
  }

}
