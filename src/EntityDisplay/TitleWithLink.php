<?php

namespace Drupal\renderkit\EntityDisplay;

class TitleWithLink extends EntityDisplayBase {

  /**
   * @var string|null
   */
  protected $tagName;

  /**
   * @var mixed[]
   */
  protected $attributes = array();

  /**
   * @param string|null $tagName
   *   A tag name to wrap the link, e.g. 'h2' or 'div'.
   *   If NULL, the link will not be wrapped.
   */
  function __construct($tagName = NULL) {
    $this->tagName = $tagName;
  }

  /**
   * @param string $class
   */
  function addClass($class) {
    $this->attributes['class'][] = $class;
  }

  /**
   * @param $entity_type
   * @param $entity
   *
   * @return array
   */
  protected function buildOne($entity_type, $entity) {
    $uri = entity_uri($entity_type, $entity);
    $link_title = entity_label($entity_type, $entity);
    if (empty($this->tagName)) {
      // Attributes directly on the link.
      if (!empty($this->attributes)) {
        $uri['options']['attributes'] = $this->attributes;
      }
      $markup = l($link_title, $uri['path'], $uri['options']);
    }
    else {
      // Attributes on a wrapper element.
      $markup = l($link_title, $uri['path'], $uri['options']);
      $attributes = !empty($this->attributes)
        ? drupal_attributes($this->attributes)
        : '';
      $markup = '<' . $this->tagName . $attributes . '>' . $markup . '</' . $this->tagName . '>';
    }
    return array(
      '#markup' => $markup,
    );
  }
}
