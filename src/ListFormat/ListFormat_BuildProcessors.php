<?php

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

class ListFormat_BuildProcessors implements ListFormatInterface {

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private $outerBuildProcessor;

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private $itemBuildProcessor;

  /**
   * @CfrPlugin(
   *   id = "buildProcessor",
   *   label = @t("Build processor")
   * )
   *
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $outerBuildProcessor
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $itemBuildProcessor
   *
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  static function create(BuildProcessorInterface $outerBuildProcessor = NULL, BuildProcessorInterface $itemBuildProcessor = NULL) {
    if (NULL === $outerBuildProcessor) {
      if (NULL === $itemBuildProcessor) {
        return new ListFormat_Bare();
      }
      else {
        return new ListFormat_ItemBuildProcessor($itemBuildProcessor);
      }
    }
    else {
      if (NULL === $itemBuildProcessor) {
        return new ListFormat_BuildProcessor($outerBuildProcessor);
      }
      else {
        return new ListFormat_BuildProcessors($outerBuildProcessor, $itemBuildProcessor);
      }
    }
  }

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $outerBuildProcessor
   *   Process the render array for the entire list.
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $itemBuildProcessor
   *   Process the render array for each list item.
   */
  function __construct(BuildProcessorInterface $outerBuildProcessor, BuildProcessorInterface $itemBuildProcessor) {
    $this->outerBuildProcessor = $outerBuildProcessor;
    $this->itemBuildProcessor = $itemBuildProcessor;
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
    foreach ($builds as $delta => $build) {
      $builds[$delta] = $this->itemBuildProcessor->process($build);
    }
    return $this->outerBuildProcessor->process($builds);
  }
}
