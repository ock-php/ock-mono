<?php

namespace Donquixote\Ock\Text;

use Donquixote\Ock\Translator\TranslatorInterface;

class Text_ReplaceOne extends TextBase {

  /**
   * @var \Donquixote\Ock\Text\TextInterface
   */
  private TextInterface $source;

  /**
   * @var string
   */
  private string $token;

  /**
   * @var \Donquixote\Ock\Text\TextInterface
   */
  private TextInterface $replacement;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface $source
   * @param string $token
   * @param \Donquixote\Ock\Text\TextInterface $replacement
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
