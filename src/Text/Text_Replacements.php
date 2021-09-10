<?php

namespace Donquixote\ObCK\Text;

use Donquixote\ObCK\Translator\TranslatorInterface;

class Text_Replacements extends TextBuilderBase {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface
   */
  private TextInterface $source;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private array $replacements;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface $source
   * @param \Donquixote\ObCK\Text\TextInterface[] $replacements
   */
  public function __construct(TextInterface $source, array $replacements = []) {
    self::validateReplacements(...array_values($replacements));
    $this->source = $source;
    $this->replacements = $replacements;
  }

  private static function validateReplacements(TextInterface ...$args): void {}

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
