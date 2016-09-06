<?php

namespace Drupal\renderkit\EntityDisplay;

use Drupal\renderkit\Configurator\Configurator_EntityDisplay_Title;

/**
 * The most boring entity display handler, ever.
 *
 * @CfrPlugin(
 *   id = "rawTitle",
 *   label = "Entity title, raw"
 * )
 */
class EntityDisplay_Title extends EntityDisplayBase {

  /**
   * Creates an "advanced" entity title plugin with optional link and wrapper
   * tag name, e.g. <h2>.
   *
   * @CfrPlugin(
   *   id = "title",
   *   label = "Entity title"
   * )
   *
   * @return \Drupal\renderkit\Configurator\Configurator_EntityDisplay_Title
   */
  public static function createConfigurator() {
    return Configurator_EntityDisplay_Title::create();
  }

  /**
   * @param $entity_type
   * @param $entity
   *
   * @return array
   */
  public function buildEntity($entity_type, $entity) {
    return array(
      '#markup' => entity_label($entity_type, $entity),
    );
  }

}
