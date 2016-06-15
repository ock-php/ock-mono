<?php

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

/**
 * @CfrPlugin("itemBuildProcessor", "Item build processor")
 */
class ListFormat_ItemBuildProcessor implements ListFormatInterface {

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private $itemBuildProcessor;

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $itemBuildProcessor
   *   Process the render array for each list item.
   */
  function __construct(BuildProcessorInterface $itemBuildProcessor) {
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
    foreach ($builds as &$itemBuild) {
      /** @noinspection ReferenceMismatchInspection */
      $itemBuild = $this->itemBuildProcessor->process($itemBuild);
    }
    return $builds;
  }
}
