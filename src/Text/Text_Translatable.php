<?php

namespace Donquixote\OCUI\Text;

use Donquixote\OCUI\Translator\TranslatorInterface;

class Text_Translatable implements TextInterface {

  private string $source;

  /**
   * Constructor.
   *
   * @param string $source
   */
  public function __construct(string $source) {
    $this->source = $source;
  }

  /**
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   *
   * @return string
   */
  public function convert(TranslatorInterface $translator): string {
     return $translator->translate($this->source);
  }

}
