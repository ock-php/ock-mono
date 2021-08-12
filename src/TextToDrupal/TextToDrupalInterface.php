<?php

declare(strict_types=1);

namespace Drupal\cu\TextToDrupal;

use Donquixote\OCUI\Text\TextInterface;
use Drupal\Component\Render\MarkupInterface;

interface TextToDrupalInterface {

  /**
   * @param \Donquixote\OCUI\Text\TextInterface $text
   *
   * @return \Drupal\Component\Render\MarkupInterface
   */
  public function convert(TextInterface $text): MarkupInterface;

}
