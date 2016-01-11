<?php

namespace Drupal\renderkit\BuildProcessor;

use Drupal\renderkit\ThemePreprocessor\ThemePreprocessorInterface;

class BuildProcessor_ThemePreprocess implements BuildProcessorInterface {

  /**
   * @var \Drupal\renderkit\ThemePreprocessor\ThemePreprocessorInterface
   */
  private $themePreprocessor;

  /**
   * @CfrPlugin(
   *   id = "themePreprocess",
   *   name = @t("Theme preprocessor")
   * )
   *
   * @param \Drupal\renderkit\ThemePreprocessor\ThemePreprocessorInterface $themePreprocessor
   */
  function __construct(ThemePreprocessorInterface $themePreprocessor) {
    $this->themePreprocessor = $themePreprocessor;
  }

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  function process(array $build) {
    $build[THEMEKIT_PREPROCESS][] = $this->themePreprocessor;
  }
}
