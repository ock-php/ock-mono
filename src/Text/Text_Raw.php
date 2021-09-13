<?php

namespace Donquixote\Ock\Text;

use Donquixote\Ock\Translator\TranslatorInterface;

/**
 * Raw text, potentially unsafe for output.
 */
class Text_Raw extends TextBuilderBase {

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
   * {@inheritdoc}
   */
  public function convert(TranslatorInterface $translator): string {
    return $this->source;
  }

}
