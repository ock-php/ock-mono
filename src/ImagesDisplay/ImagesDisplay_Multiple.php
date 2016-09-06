<?php

namespace Drupal\renderkit\ImagesDisplay;

use Drupal\renderkit\ListFormat\ListFormatInterface;

class ImagesDisplay_Multiple implements ImagesDisplayInterface {

  /**
   * @var \Drupal\renderkit\ImagesDisplay\ImagesDisplayInterface[]
   */
  private $displays;

  /**
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface|null
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit\ImagesDisplay\ImagesDisplayInterface[] $displays
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $listFormat
   */
  public function __construct(array $displays, ListFormatInterface $listFormat = NULL) {
    $this->displays = $displays;
    $this->listFormat = $listFormat;
  }

  /**
   * @param array[] $images
   *   Format: $[$delta] = ['#theme' => 'image', '#path' => .., ..]
   *
   * @return array
   *   A Drupal render array.
   */
  public function buildImages(array $images) {
    $build = array();
    foreach ($this->displays as $delta => $display) {
      $build[$delta] = $display->buildImages($images);
    }
    if (NULL !== $this->listFormat) {
      $build = $this->listFormat->buildList($build);
    }
    return $build;
  }
}
