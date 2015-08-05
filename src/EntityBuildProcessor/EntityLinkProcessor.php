<?php

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\renderkit\Html\HtmlAttributesInterface;
use Drupal\renderkit\Html\HtmlAttributesTrait;

/**
 * Wraps the content from a decorated display handler into a link, linking to
 * the entity.
 *
 * Just like the base class, this does provide methods like addClass() to modify
 * the attributes of the link element.
 *
 * A typical use case would be to wrap in image into a link element.
 */
class EntityLinkProcessor extends EntityBuildProcessorBase implements HtmlAttributesInterface {

  use HtmlAttributesTrait;

  /**
   * @param array $build
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  function processOne(array $build, $entity_type, $entity) {
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
