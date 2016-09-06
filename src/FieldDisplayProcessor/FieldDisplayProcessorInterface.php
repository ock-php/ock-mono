<?php

namespace Drupal\renderkit\FieldDisplayProcessor;

interface FieldDisplayProcessorInterface {

  /**
   * @param array $build
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  public function process(array $build);

}
