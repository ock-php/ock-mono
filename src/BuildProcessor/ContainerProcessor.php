<?php

namespace Drupal\renderkit\BuildProcessor;

use Drupal\renderkit\Attributes\AttributesInterface;
use Drupal\renderkit\Attributes\TagTrait;

class ContainerProcessor extends BuildProcessorBase implements AttributesInterface {

  use TagTrait;

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  function process(array $build) {
    return $this->buildContainer() + array($build);
  }
}
