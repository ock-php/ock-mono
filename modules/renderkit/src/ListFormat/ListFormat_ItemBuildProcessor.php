<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Ock\Ock\Attribute\Parameter\OckDecorated;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * @todo Mark as decorator.
 */
#[OckPluginInstance('itemBuildProcessor', 'Item build processor')]
class ListFormat_ItemBuildProcessor implements ListFormatInterface {

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $itemBuildProcessor
   *   Process the render array for each list item.
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $decorated
   */
  public function __construct(
    #[OckOption('itemBuildProcessor', 'Item build processor')]
    private readonly BuildProcessorInterface $itemBuildProcessor,
    #[OckDecorated]
    private readonly ?ListFormatInterface $decorated = NULL,
  ) {}

  /**
   * {@inheritdoc}
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
