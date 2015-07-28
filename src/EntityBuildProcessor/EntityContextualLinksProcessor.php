<?php

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\renderkit\Attributes\AttributesInterface;
use Drupal\renderkit\Attributes\TagTrait;

/**
 * A typical entity container with contextual links and stuff.
 */
class EntityContextualLinksProcessor extends EntityBuildProcessorBase implements AttributesInterface {

  use TagTrait;

  function __construct() {
    $this->setTagName('article');
  }

  /**
   * @param array $build
   *   Render array before the processing.
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array after the processing.
   */
  function processOne(array $build, $entity_type, $entity) {
    $build = $this->buildContainer() + array('content' => $build);
    if (!user_access('access contextual links')) {
      return $build;
    }
    // Initialize the template variable as a renderable array.
    $entity_uri = entity_uri($entity_type, $entity);
    if (!isset($entity_uri['path'])) {
      return $build;
    }
    $entity_id = entity_id($entity_type, $entity);
    if (empty($entity_id)) {
      return $build;
    }
    if (FALSE === $pos = strpos($entity_uri['path'] . '/', '/' . $entity_id . '/')) {
      return $build;
    }
    $base_path = substr($entity_uri['path'], 0, $pos);
    $build['contextual_links'] = array(
      '#type' => 'contextual_links',
      '#contextual_links' => array(
        $entity_type => array($base_path, array($entity_id)),
      ),
      '#element' => $build,
    );
    // Mark this element as potentially having contextual links attached to it.
    $build['#attributes']['class'][] = 'contextual-links-region';
    return $build;
  }
}
