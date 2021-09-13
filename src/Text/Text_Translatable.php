<?php

namespace Donquixote\Ock\Text;

use Donquixote\Ock\Translator\TranslatorInterface;

class Text_Translatable extends TextBuilderBase {

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
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   *
   * @return string
   */
  public function convert(TranslatorInterface $translator): string {
     return $translator->translate($this->source);
  }

}
