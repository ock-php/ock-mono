<?php

namespace Donquixote\Ock\Text;

use Donquixote\Ock\Translator\TranslatorInterface;

class Text_Sprintf extends TextBase {

  /**
   * @var string
   */
  private string $source;

  /**
   * @var \Donquixote\Ock\Text\TextInterface
   */
  private TextInterface $replacement;

  /**
   * Constructor.
   *
   * @param string $source
   * @param \Donquixote\Ock\Text\TextInterface $replacement
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
