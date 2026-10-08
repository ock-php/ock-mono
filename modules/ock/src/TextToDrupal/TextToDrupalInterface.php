<?php

declare(strict_types=1);

namespace Drupal\ock\TextToDrupal;

use Drupal\Component\Render\MarkupInterface;
use Ock\Text\TextInterface;

interface TextToDrupalInterface {

  /**
   * @param \Ock\Text\TextInterface $text
   *
   * @return \Drupal\Component\Render\MarkupInterface
   */
  public function convert(TextInterface $text): MarkupInterface;

}
