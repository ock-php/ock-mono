<?php

namespace Drupal\renderkit8\ThemePreprocessor;

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
