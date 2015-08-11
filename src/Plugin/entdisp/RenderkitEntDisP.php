<?php

namespace Drupal\renderkit\Plugin\entdisp;

use Drupal\renderkit\EntityDisplay\TitleEntityDisplay;
use Drupal\renderkit\EntityDisplay\TitleLinkEntityDisplay;
use Drupal\renderkit\Renderkit;

/**
 * Contains static factory methods that are registered as entdisp plugins.
 *
 * @see renderkit_entdisp_info()
 */
class RenderkitEntDisP {

  /**
   * Title with link
   *
   * @param string|null $wrapperTagName
   *   E.g. 'h2'.
   *
   * @return \Drupal\renderkit\EntityDisplay\Html\HtmlAttributesEntityDisplayInterface
   *
   * @plugin
   */
  static function entityTitleLink($wrapperTagName = NULL) {
    $entityDisplay = new TitleLinkEntityDisplay();
    if (isset($wrapperTagName)) {
      $entityDisplay = Renderkit::entityContainer($entityDisplay, $wrapperTagName);
    }
    return $entityDisplay;
  }

  /**
   * Title
   *
   * @param string $wrapperTagName
   *
   * @return \Drupal\renderkit\EntityDisplay\Html\HtmlAttributesEntityDisplayInterface
   *
   * @plugin
   */
  static function entityTitle($wrapperTagName = 'h2') {
    return Renderkit::entityContainer(new TitleEntityDisplay(), $wrapperTagName);
  }

}
