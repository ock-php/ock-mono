<?php

namespace Donquixote\ObCK\Text;

use Donquixote\ObCK\Translator\TranslatorInterface;

class Text_Sprintf extends TextBase {

  /**
   * @var string
   */
  private string $source;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface
   */
  private TextInterface $replacement;

  /**
   * Constructor.
   *
   * @param string $source
   * @param \Donquixote\ObCK\Text\TextInterface $replacement
   */
  public function __construct(string $source, TextInterface $replacement) {
    $this->source = $source;
    $this->replacement = $replacement;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TranslatorInterface $translator): string {
    return sprintf(
      $this->source,
      $this->replacement->convert($translator));
  }

}
