<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Ock\Ock\Attribute\Parameter\OckDecorated;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Decorator that processes the result from the decorated build provider.
 *
 * @todo Mark as decorator.
 */
#[OckPluginInstance('processed', 'Processed')]
class BuildProvider_Processed implements BuildProviderInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\BuildProvider\BuildProviderInterface $decorated
   *   The decorated build provider.
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   *   A processor to process the result render element.
   */
  public function __construct(
    #[OckDecorated]
    private readonly BuildProviderInterface $decorated,
    #[OckOption('processor', 'Processor')]
    private readonly BuildProcessorInterface $processor,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build = $this->decorated->build();
    if ($build) {
      $build = $this->processor->process($build);
    }
    return $build;
  }
}
