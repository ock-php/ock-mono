<?php

namespace Donquixote\Ock\Text;

use Donquixote\Ock\Translator\TranslatorInterface;

class Text_Replacements extends TextBuilderBase {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface $source
   * @param \Donquixote\Ock\Text\TextInterface[] $replacements
   */
  public function __construct(
    private readonly TextInterface $source,
    private array $replacements = [],
  ) {
    Text::validateMultiple($replacements);
  }

  /**
   * {@inheritdoc}
   */
  public function replace(string $token, TextInterface $replacement): TextBuilderBase {
    $clone = clone $this;
    $clone->replacements[$token] = $replacement;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TranslatorInterface $translator): string {
    $translated = $this->source->convert($translator);
    if (!$this->replacements) {
      return $translated;
    }
    $replacements = [];
    foreach ($this->replacements as $key => $replacement) {
      $replacements[$key] = $replacement->convert($translator);
    }
    return strtr($translated, $replacements);
  }

}
