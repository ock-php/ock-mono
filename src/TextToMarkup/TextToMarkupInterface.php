<?php

namespace Donquixote\ObCK\TextToMarkup;

use Donquixote\ObCK\Text\TextInterface;

interface TextToMarkupInterface {

  /**
   * @param \Donquixote\ObCK\Text\TextInterface $text
   *
   * @return string
   */
  public function textGetMarkup(TextInterface $text): string;

}
