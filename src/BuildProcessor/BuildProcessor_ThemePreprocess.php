<?php

namespace Drupal\renderkit8\BuildProcessor;

use Drupal\renderkit8\ThemePreprocessor\ThemePreprocessorInterface;

class BuildProcessor_ThemePreprocess implements BuildProcessorInterface {

  /**
   * @var \Drupal\renderkit8\ThemePreprocessor\ThemePreprocessorInterface
   */
  private $themePreprocessor;

  /**
   * @CfrPlugin(
   *   id = "themePreprocess",
   *   name = @t("Theme preprocessor")
   * )
   *
   * @param \Drupal\renderkit8\ThemePreprocessor\ThemePreprocessorInterface $themePreprocessor
   */
  public function __construct(ThemePreprocessorInterface $themePreprocessor) {
    $this->themePreprocessor = $themePreprocessor;
  }

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  public function process(array $build) {
    $build[THEMEKIT_PREPROCESS][] = $this->themePreprocessor;
    return $build;
  }
}
