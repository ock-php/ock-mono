<?php
declare(strict_types=1);

namespace Drupal\themekit\Element;

use Drupal\Core\Render\Element\RenderElementBase;

/**
 * @RenderElement("themekit_container")
 *
 * @see \Drupal\Core\Render\Element\Container
 */
class RenderElement_ThemekitContainer extends RenderElementBase {

  public const ID = 'themekit_container';

  /**
   * Returns the element properties for this element.
   *
   * @return array
   *   An array of element properties. See
   *   \Drupal\Core\Render\ElementInfoManagerInterface::getInfo() for
   *   documentation of the standard properties of all elements, and the
   *   return value format.
   */
  public function getInfo(): array {
    return [
      '#theme_wrappers' => ['themekit_container'],
    ];
  }

}
