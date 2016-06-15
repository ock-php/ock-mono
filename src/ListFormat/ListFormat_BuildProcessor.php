<?php

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

/**
 * @CfrPlugin("buildProcessor", "Build processor")
 */
class ListFormat_BuildProcessor implements ListFormatInterface {

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private $buildProcessor;

  /**
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface|null
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $buildProcessor
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $decorated
   */
  function __construct(BuildProcessorInterface $buildProcessor, ListFormatInterface $decorated = NULL) {
    $this->buildProcessor = $buildProcessor;
    $this->decorated = $decorated;
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
    if (NULL !== $this->decorated) {
      $builds = $this->decorated->buildList($builds);
    }
    return $this->buildProcessor->process($builds);
  }
}
