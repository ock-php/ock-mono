<?php
declare(strict_types=1);

namespace Drupal\renderkit8\LabeledListFormat;

use Drupal\renderkit8\BuildProcessor\BuildProcessorInterface;
use Drupal\renderkit8\LabeledFormat\LabeledFormatInterface;
use Drupal\renderkit8\ListFormat\ListFormatInterface;

/**
 * @CfrPlugin("composite", "Composite")
 */
class LabeledListFormat_Composite implements LabeledListFormatInterface {

  /**
   * @var \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface|NULL
   */
  private $outerProcessor;

  /**
   * @var \Drupal\renderkit8\LabeledFormat\LabeledFormatInterface|NULL
   */
  private $labeledFormat;

  /**
   * @var \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface|NULL
   */
  private $innerProcessor;

  /**
   * @var \Drupal\renderkit8\ListFormat\ListFormatInterface|NULL
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface|NULL $outerProcessor
   * @param \Drupal\renderkit8\LabeledFormat\LabeledFormatInterface|NULL $labeledFormat
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface|NULL $innerProcessor
   * @param \Drupal\renderkit8\ListFormat\ListFormatInterface|NULL $listFormat
   */
  public function __construct(
    BuildProcessorInterface $outerProcessor = NULL,
    LabeledFormatInterface $labeledFormat = NULL,
    BuildProcessorInterface $innerProcessor = NULL,
    ListFormatInterface $listFormat = NULL
  ) {
    $this->outerProcessor = $outerProcessor;
    $this->labeledFormat = $labeledFormat;
    $this->innerProcessor = $innerProcessor;
    $this->listFormat = $listFormat;
  }

  /**
   * @param array[] $builds
   *   Render arrays, e.g. for field items or field group children.
   * @param string $label
   *   A label, e.g. for a field or field group.
   *
   * @return array
   *   Combined render array.
   */
  public function build(array $builds, $label) {
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
