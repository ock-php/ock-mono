<?php

namespace Drupal\renderkit8\ListFormat;

use Drupal\renderkit8\BuildProcessor\BuildProcessorInterface;

/**
 * @CfrPlugin("itemBuildProcessor", "Item build processor")
 */
class ListFormat_ItemBuildProcessor implements ListFormatInterface {

  /**
   * @var \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface
   */
  private $itemBuildProcessor;

  /**
   * @var \Drupal\renderkit8\ListFormat\ListFormatInterface|null
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface $itemBuildProcessor
   *   Process the render array for each list item.
   * @param \Drupal\renderkit8\ListFormat\ListFormatInterface|null $decorated
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

    foreach ($builds as $delta => $itemBuild) {
      $builds[$delta] = $this->itemBuildProcessor->process($itemBuild);
    }

    if (NULL !== $this->decorated) {
      $builds = $this->decorated->buildList($builds);
    }

    return $builds;
  }
}
