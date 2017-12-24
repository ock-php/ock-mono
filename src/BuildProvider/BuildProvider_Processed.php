<?php
declare(strict_types=1);

namespace Drupal\renderkit8\BuildProvider;

use Drupal\renderkit8\BuildProcessor\BuildProcessorInterface;

/**
 * @CfrPlugin("processed", "Processed")
 */
class BuildProvider_Processed implements BuildProviderInterface {

  /**
   * @var \Drupal\renderkit8\BuildProvider\BuildProviderInterface
   */
  private $decorated;

  /**
   * @var \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface
   */
  private $processor;

  /**
   * @param \Drupal\renderkit8\BuildProvider\BuildProviderInterface $decorated
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface $processor
   */
  public function __construct(BuildProviderInterface $decorated, BuildProcessorInterface $processor) {
    $this->decorated = $decorated;
    $this->processor = $processor;
  }

  /**
   * @return array
   *   A render array.
   */
  public function build() {
    $build = $this->decorated->build();
    if ($build) {
      $build = $this->processor->process($build);
    }
    return $build;
  }
}
