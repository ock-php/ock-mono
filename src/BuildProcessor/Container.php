<?php

namespace Drupal\renderkit\BuildProcessor;

use Drupal\renderkit\Attributes\AttributesBase;

class Container extends AttributesBase implements BuildProcessorInterface {

  /**
   * @param string $tagName
   *
   * @return $this
   */
  function setTagName($tagName) {
    $this->tagName = $tagName;
    return $this;
  }

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  function process(array $build) {
    return array(
      $build,
      /* @see renderkit_element_info() */
      '#type' => 'renderkit_container',
      '#attributes' => $this->getAttributes(),
    );
  }
}
