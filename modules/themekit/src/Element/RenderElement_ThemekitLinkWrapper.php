<?php
declare(strict_types=1);

namespace Drupal\themekit\Element;

use Drupal\Core\Render\Attribute\RenderElement;
use Drupal\Core\Render\Element\RenderElementBase;

/**
 * @see \Drupal\Core\Render\Element\Link
 * @see \Drupal\Core\Render\Element\Container
 */
#[RenderElement(self::ID)]
class RenderElement_ThemekitLinkWrapper extends RenderElementBase {

  public const ID = 'themekit_link_wrapper';

  /**
   * Returns the element properties for this element.
   *
   * @return array
   */
  public function getInfo(): array {
    return [
      '#theme_wrappers' => ['themekit_link_wrapper'],
    ];
  }

}
