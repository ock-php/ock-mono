<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

/**
 * @CfrPlugin("itemBuildProcessor", "Item build processor")
 */
class ListFormat_ItemBuildProcessor implements ListFormatInterface {

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $itemBuildProcessor
   *   Process the render array for each list item.
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $decorated
   */
  public function __construct(
    private readonly BuildProcessorInterface $itemBuildProcessor,
    private readonly ?ListFormatInterface $decorated = NULL,
  ) {}

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  public function buildList(array $builds): array {

    foreach ($builds as $delta => $itemBuild) {
      $builds[$delta] = $this->itemBuildProcessor->process($itemBuild);
    }

    if (NULL !== $this->decorated) {
      $builds = $this->decorated->buildList($builds);
    }

    return $builds;
  }
}
