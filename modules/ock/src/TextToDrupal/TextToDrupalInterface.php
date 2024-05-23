<?php

declare(strict_types=1);

namespace Drupal\ock\TextToDrupal;

use Donquixote\Ock\Text\TextInterface;
use Drupal\Component\Render\MarkupInterface;

interface TextToDrupalInterface {

  /**
   * @param \Donquixote\Ock\Text\TextInterface $text
   *
   * @return \Drupal\Component\Render\MarkupInterface
   */
  public function convert(TextInterface $text): MarkupInterface;

}
