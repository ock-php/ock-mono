<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;
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
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createAdvancedSchema() {
    return CfSchema_EntityDisplay_Title::create()->getValSchema();
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {
    return [
      '#markup' => $entity->label(),
    ];
  }

}
