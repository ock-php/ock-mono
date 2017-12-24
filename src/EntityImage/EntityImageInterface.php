<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityImage;

use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

/**
 * Interface for entity image providers.
 *
 * For each entity, the image provider returns a render array with
 * '#theme' => 'image' and keys for alt and stuff, which can later be used to
 * build image styles and the like.
 */
interface EntityImageInterface extends EntityDisplayInterface {

  /**
   * Same method signature as in parent interface, just a different description.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   */
  public function buildEntities(array $entities);
}
