<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledListFormat;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Drupal\renderkit\LabeledFormat\LabeledFormatInterface;
use Drupal\renderkit\ListFormat\ListFormatInterface;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('composite', 'Composite')]
class LabeledListFormat_Composite implements LabeledListFormatInterface {

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface|null $outerProcessor
   * @param \Drupal\renderkit\LabeledFormat\LabeledFormatInterface|null $labeledFormat
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface|null $innerProcessor
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $listFormat
   */
  public function __construct(
    #[OckOption('outerProcessor', 'Outer processor')]
    private readonly ?BuildProcessorInterface $outerProcessor = NULL,
    #[OckOption('labeledFormat', 'Labeled format')]
    private readonly ?LabeledFormatInterface $labeledFormat = NULL,
    #[OckOption('innerProcessor', 'Inner processor')]
    private readonly ?BuildProcessorInterface $innerProcessor = NULL,
    #[OckOption('listFormat', 'List format')]
    private readonly ?ListFormatInterface $listFormat = NULL
  ) {}

  /**
   * @param array[] $builds
   *   Render arrays, e.g. for field items or field group children.
   * @param string $label
   *   A label, e.g. for a field or field group.
   *
   * @return array
   *   Combined render array.
   */
  public function build(array $builds, string $label): array {
    $build = (NULL !== $this->listFormat)
      ? $this->listFormat->buildList($builds)
      : $builds;
    if (NULL !== $this->innerProcessor) {
      $build = $this->innerProcessor->process($build);
    }
    if (NULL !== $this->labeledFormat) {
      $build = $this->labeledFormat->buildAddLabel($build, $label);
    }
    if (NULL !== $this->outerProcessor) {
      $build = $this->outerProcessor->process($build);
    }
    return $build;
  }
}
