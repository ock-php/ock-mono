<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImagesDisplay;

use Drupal\renderkit\ListFormat\ListFormatInterface;

class ImagesDisplay_Multiple implements ImagesDisplayInterface {

  /**
   * @param \Drupal\renderkit\ImagesDisplay\ImagesDisplayInterface[] $displays
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $listFormat
   */
  public function __construct(
    private readonly array $displays,
    private readonly ?ListFormatInterface $listFormat = NULL,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildImages(array $images): array {
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
