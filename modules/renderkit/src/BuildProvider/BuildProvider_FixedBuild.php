<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

class BuildProvider_FixedBuild implements BuildProviderInterface {

  /**
   * @param array $build
   */
  public function __construct(
    private readonly array $build,
  ) {}

  /**
   * @return array
   *   A render array.
   */
  public function build(): array {
    return $this->build;
  }
}
