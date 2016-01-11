<?php

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

class ListFormat_BuildProcessor implements ListFormatInterface {

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private $buildProcessor;

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $buildProcessor
   */
  function __construct(BuildProcessorInterface $buildProcessor) {
    $this->buildProcessor = $buildProcessor;
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  function buildList(array $builds) {
    return $this->buildProcessor->process($builds);
  }
}
