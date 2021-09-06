<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\Formula\Formula_EntityDisplay_Title;

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
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createAdvancedFormula(): FormulaInterface {
    return Formula_EntityDisplay_Title::create()->getValFormula();
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity): array {
    return [
      '#markup' => $entity->label(),
    ];
  }

}
