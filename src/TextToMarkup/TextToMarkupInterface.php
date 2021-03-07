<?php

namespace Donquixote\OCUI\TextToMarkup;

use Donquixote\OCUI\Text\TextInterface;

interface TextToMarkupInterface {

  /**
   * @param \Donquixote\OCUI\Text\TextInterface $text
   *
   * @return string
   */
  public function textGetMarkup(TextInterface $text): string;

}
