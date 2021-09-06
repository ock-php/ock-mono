<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProcessor;

interface BuildProcessorInterface {

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  public function process(array $build);

}
