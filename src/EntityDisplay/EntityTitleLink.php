<?php

namespace Drupal\renderkit\EntityDisplay;

use Drupal\renderkit\Html\HtmlAttributesTrait;

/**
 * Renders an entity title linking to the entity.
 *
 * Does not by itself add any wrapper tags, like "h2".
 * To add those, wrap this handler into a container decorator.
 */
class EntityTitleLink extends EntityDisplayBase {

  use HtmlAttributesTrait;

  /**
   * @param $entity_type
   * @param $entity
   *
   * @return array
   */
  protected function buildOne($entity_type, $entity) {
    $uri = entity_uri($entity_type, $entity);
    $text = entity_label($entity_type, $entity);
    // Add required keys for theme_link().
    $uri += array('options' => array());
    $uri['options'] += array('attributes' => array(), 'html' => FALSE);
    // Attributes directly on the link.
    $uri['options']['attributes'] += $this->attributes;
    return array(
      /* @see theme_link() */
      '#theme' => 'link',
      '#text' => $text,
      '#path' => $uri['path'],
      '#options' => $uri['options'],
    );
  }
}
