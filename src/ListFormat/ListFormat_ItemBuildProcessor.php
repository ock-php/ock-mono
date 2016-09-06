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
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface|null
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $itemBuildProcessor
   *   Process the render array for each list item.
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $decorated
   */
  public function __construct(BuildProcessorInterface $itemBuildProcessor, ListFormatInterface $decorated = NULL) {
    $this->itemBuildProcessor = $itemBuildProcessor;
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
  public function buildList(array $builds) {
    foreach ($builds as &$itemBuild) {
      /** @noinspection ReferenceMismatchInspection */
      $itemBuild = $this->itemBuildProcessor->process($itemBuild);
    }
    if (NULL !== $this->decorated) {
      $builds = $this->decorated->buildList($builds);
    }
    return $builds;
  }
}
