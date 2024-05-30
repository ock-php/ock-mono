<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * @todo Mark as decorator.
 */
#[OckPluginInstance('outerBuildProcessor', 'Outer build processor')]
class ListFormat_OuterBuildProcessor implements ListFormatInterface {

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private BuildProcessorInterface $buildProcessor;

  /**
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface|null
   */
  private ?ListFormatInterface $decorated;

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $outerBuildProcessor
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $decoratedListFormat
   */
  public function __construct(BuildProcessorInterface $outerBuildProcessor, ListFormatInterface $decoratedListFormat = NULL) {
    $this->buildProcessor = $outerBuildProcessor;
    $this->decorated = $decoratedListFormat;
  }

  /**
   * {@inheritdoc}
   */
  public function buildList(array $builds): array {
    if (NULL !== $this->decorated) {
      $builds = $this->decorated->buildList($builds);
    }
    return $this->buildProcessor->process($builds);
  }
}
