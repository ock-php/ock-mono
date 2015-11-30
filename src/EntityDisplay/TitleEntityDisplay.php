<?php

namespace Drupal\renderkit\EntityDisplay;

use Drupal\renderkit\UniPlugin\EntityDisplay\TitleEntityDisplayPlugin;

/**
 * The most boring entity display handler, ever.
 *
 * @UniPlugin(
 *   id = "rawTitle",
 *   label = "Entity title, raw"
 * )
 */
class TitleEntityDisplay extends EntityDisplayBase {

  /**
   * @UniPlugin(
   *   id = "title",
   *   label = "Entity title"
   * )
   *
   * @return \Drupal\renderkit\UniPlugin\EntityDisplay\TitleEntityDisplayPlugin
   */
  static function createPlugin() {
    return TitleEntityDisplayPlugin::create();
  }

  /**
   * @param $entity_type
   * @param $entity
   *
   * @return array
   */
  function buildEntity($entity_type, $entity) {
    return array(
      '#markup' => entity_label($entity_type, $entity),
    );
  }

}
