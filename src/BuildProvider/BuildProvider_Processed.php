<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

/**
 * @CfrPlugin("processed", "Processed")
 */
class BuildProvider_Processed implements BuildProviderInterface {

  /**
   * @var \Drupal\renderkit\BuildProvider\BuildProviderInterface
   */
  private $decorated;

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private $processor;

  /**
   * @param \Drupal\renderkit\BuildProvider\BuildProviderInterface $decorated
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   */
  public function __construct(BuildProviderInterface $decorated, BuildProcessorInterface $processor) {
    $this->decorated = $decorated;
    $this->processor = $processor;
  }

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
