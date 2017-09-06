<?php

namespace Drupal\themekit\Element;

use Drupal\Core\Render\Annotation\RenderElement;
use Drupal\Core\Render\Element\RenderElement as CoreRenderElement;

/**
 * @RenderElement("themekit_link_wrapper")
 *
 * @see \Drupal\Core\Render\Element\Link
 * @see \Drupal\Core\Render\Element\Container
 */
class RenderElement_ThemekitLinkWrapper extends CoreRenderElement {

  const ID = 'themekit_link_wrapper';

  /**
   * Returns the element properties for this element.
   *
   * @return array
   */
  public function getInfo() {
    return [
      /* @see theme_themekit_link_wrapper() */
      '#theme_wrappers' => ['themekit_link_wrapper'],
    ];
  }
}
