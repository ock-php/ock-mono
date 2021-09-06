<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

class BuildProvider_FixedBuild implements BuildProviderInterface {

  /**
   * @var array
   */
  private $build;

  /**
   * @param array $build
   */
  public function __construct(array $build) {
    $this->build = $build;
  }

  /**
   * @return array
   *   A render array.
   */
  public function build() {
    return $this->build;
  }
}
