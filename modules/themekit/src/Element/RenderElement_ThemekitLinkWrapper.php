<?php
declare(strict_types=1);

namespace Drupal\themekit\Element;

use Drupal\Core\Render\Element\RenderElementBase;

/**
 * @RenderElement("themekit_link_wrapper")
 *
 * @see \Drupal\Core\Render\Element\Link
 * @see \Drupal\Core\Render\Element\Container
 */
class RenderElement_ThemekitLinkWrapper extends RenderElementBase {

  public const ID = 'themekit_link_wrapper';

  /**
   * Returns the element properties for this element.
   *
   * @return array
   */
  public function getInfo() {
    return [
      '#theme_wrappers' => ['themekit_link_wrapper'],
    ];
  }
}
