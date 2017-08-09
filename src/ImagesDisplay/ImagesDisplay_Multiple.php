<?php

namespace Drupal\renderkit8\ImagesDisplay;

use Drupal\renderkit8\ListFormat\ListFormatInterface;

class ImagesDisplay_Multiple implements ImagesDisplayInterface {

  /**
   * @var \Drupal\renderkit8\ImagesDisplay\ImagesDisplayInterface[]
   */
  private $displays;

  /**
   * @var \Drupal\renderkit8\ListFormat\ListFormatInterface|null
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit8\ImagesDisplay\ImagesDisplayInterface[] $displays
   * @param \Drupal\renderkit8\ListFormat\ListFormatInterface|null $listFormat
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
    $build = [];
    foreach ($this->displays as $delta => $display) {
      $build[$delta] = $display->buildImages($images);
    }
    if (NULL !== $this->listFormat) {
      $build = $this->listFormat->buildList($build);
    }
    return $build;
  }
}
