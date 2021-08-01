<?php

namespace Donquixote\OCUI\Text;

use Donquixote\OCUI\Translator\TranslatorInterface;

/**
 * Raw text, potentially unsafe for output.
 */
class Text_Raw implements TextInterface {

  private string $source;

  /**
   * Constructor.
   *
   * @param string $source
   */
  public function __construct(string $source) {
    $this->source = $source;
  }

  public function convert(TranslatorInterface $translator): string {
    return $this->source;
  }

}
