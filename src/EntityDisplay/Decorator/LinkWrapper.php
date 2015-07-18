<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\EntityDisplay\Decorator\AttributesDecoratorBase;

/**
 * Wraps the content from a decorated display handler into a link, linking to
 * the entity.
 *
 * Just like the base class, this does provide methods like addClass() to modify
 * the attributes of the link element.
 *
 * A typical use case would be to wrap in image into a link element.
 */
class LinkWrapper extends AttributesDecoratorBase {

  /**
   * @param array $build
   * @param string $entity_type
   * @param object $entity
   *
   * @return array Render array for one entity.
   * Render array for one entity.
   */
  protected function decorateOne($build, $entity_type, $entity) {
    $link_uri = entity_uri($entity_type, $entity);
    $link_uri['options']['attributes'] = $this->attributes;
    return array(
      $build,
      /* @see renderkit_element_info() */
      '#type' => 'renderkit_link_wrapper',
      '#path' => $link_uri['path'],
      '#options' => $link_uri['options'],
    );
  }

}
