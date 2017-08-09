<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\renderkit8\Schema\CfSchema_EntityDisplay_Title;

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
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createAdvancedSchema() {
    return CfSchema_EntityDisplay_Title::create()->getValSchema();
  }

  /**
   * @param $entity_type
   * @param $entity
   *
   * @return array
   */
  public function buildEntity($entity_type, $entity) {
    return [
      '#markup' => check_plain(entity_label($entity_type, $entity)),
    ];
  }

}
