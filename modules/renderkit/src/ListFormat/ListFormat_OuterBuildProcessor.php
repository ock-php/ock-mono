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
#[OckPluginInstance('outerBuildProcessor', 'Outer build processor')]
class ListFormat_OuterBuildProcessor implements ListFormatInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $buildProcessor
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $decorated
   */
  public function __construct(
    #[OckOption('outerBuildProcessor', 'Outer build processor')]
    private readonly BuildProcessorInterface $buildProcessor,
    #[OckDecorated]
    private readonly ?ListFormatInterface $decorated = NULL,
  ) {}

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
