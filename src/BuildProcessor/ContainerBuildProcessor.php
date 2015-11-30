<?php

namespace Drupal\renderkit\BuildProcessor;

use Drupal\renderkit\Html\HtmlAttributesInterface;
use Drupal\renderkit\Html\HtmlTagTrait;

class ContainerBuildProcessor implements HtmlAttributesInterface, BuildProcessorInterface {

  use HtmlTagTrait;

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  function process(array $build) {
    return $this->buildContainer() + array('content' => $build);
  }
}
