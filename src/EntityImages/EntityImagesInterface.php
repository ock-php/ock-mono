<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityImages;

use Drupal\Core\Entity\EntityInterface;

interface EntityImagesInterface {

  /**
   * Gets multiple render arrays with '#theme' => 'image' for a given entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array[]
   *   Format: $[$delta] = ['#theme' => 'image', '#path' => .., ..]
   */
  public function entityGetImages(EntityInterface $entity): array;

}
