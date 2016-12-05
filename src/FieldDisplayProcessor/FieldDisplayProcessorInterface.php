<?php

namespace Drupal\renderkit\FieldDisplayProcessor;

interface FieldDisplayProcessorInterface {

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  public function process(array $element);

}
