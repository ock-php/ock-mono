<?php

namespace Drupal\renderkit\Plugin\entdisp;

use Drupal\renderkit\EntityDisplay\EntityTitle;
use Drupal\renderkit\EntityDisplay\EntityTitleLink;
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
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayAttributesInterface
   *
   * @plugin
   */
  static function entityTitleLink($wrapperTagName = NULL) {
    $entityDisplay = new EntityTitleLink();
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
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayAttributesInterface
   *
   * @plugin
   */
  static function entityTitle($wrapperTagName = 'h2') {
    return Renderkit::entityContainer(new EntityTitle(), $wrapperTagName);
  }

}
