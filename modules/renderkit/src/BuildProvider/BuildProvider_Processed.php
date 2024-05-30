<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('processed', 'Processed')]
class BuildProvider_Processed implements BuildProviderInterface {

  /**
   * @param \Drupal\renderkit\BuildProvider\BuildProviderInterface $decorated
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   */
  public function __construct(
    private readonly BuildProviderInterface $decorated,
    private readonly BuildProcessorInterface $processor,
  ) {}

  /**
   * @return array
   *   A render array.
   */
  public function build(): array {
    $build = $this->decorated->build();
    if ($build) {
      $build = $this->processor->process($build);
    }
    return $build;
  }
}
