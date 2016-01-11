<?php

namespace Drupal\renderkit\BuildProvider;

class BuildProvider_FixedBuild implements BuildProviderInterface {

  /**
   * @var array
   */
  private $build;

  /**
   * @param array $build
   */
  function __construct(array $build) {
    $this->build = $build;
  }

  /**
   * @return array
   *   A render array.
   */
  function build() {
    return $this->build;
  }
}
