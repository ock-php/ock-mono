<?php

namespace Donquixote\Ock\Text;

use Donquixote\Ock\Translator\TranslatorInterface;

class Text_ReplaceOne extends TextBase {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface $source
   * @param string $token
   * @param \Donquixote\Ock\Text\TextInterface $replacement
   */
  public function __construct(
    private readonly TextInterface $source,
    private readonly string $token,
    private readonly TextInterface $replacement,
  ) {}

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
