<?php

namespace Drupal\renderkit8\EntityImages;

interface EntityImagesInterface {

  /**
   * Gets multiple render arrays with '#theme' => 'image' for a given entity.
   * 
   * @param string $entityType
   * @param object $entity
   *
   * @return array[]
   *   Format: $[$delta] = ['#theme' => 'image', '#path' => .., ..]
   */
  public function entityGetImages($entityType, $entity);

}
