<?php

namespace Donquixote\ObCK\Text;

use Donquixote\ObCK\Translator\TranslatorInterface;

class Text_ReplaceOne extends TextBase {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface
   */
  private TextInterface $source;

  /**
   * @var string
   */
  private string $token;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface
   */
  private TextInterface $replacement;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface $source
   * @param string $token
   * @param \Donquixote\ObCK\Text\TextInterface $replacement
   */
  public function __construct(TextInterface $source, string $token, TextInterface $replacement) {
    $this->source = $source;
    $this->token = $token;
    $this->replacement = $replacement;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TranslatorInterface $translator): string {
    return str_replace(
      $this->token,
      $this->replacement->convert($translator),
      $this->source->convert($translator));
  }

}
