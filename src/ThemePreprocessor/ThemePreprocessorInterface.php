<?php

namespace Drupal\renderkit\ThemePreprocessor;

interface ThemePreprocessorInterface {

  /**
   * @see hook_preprocess()
   * @see theme()
   *
   * @param array $variables
   * @param string $hook
   */
  public function __invoke(&$variables, $hook);

}
