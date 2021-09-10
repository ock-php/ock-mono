<?php

namespace Donquixote\ObCK\Text;

use Donquixote\ObCK\Translator\TranslatorInterface;

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
