<?php

namespace Donquixote\OCUI\Text;

use Donquixote\OCUI\Translator\TranslatorInterface;

class Text_Replacements implements TextInterface {

  /**
   * @var \Donquixote\OCUI\Text\TextInterface
   */
  private TextInterface $source;

  /**
   * @var \Donquixote\OCUI\Text\TextInterface[]
   */
  private array $replacements;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Text\TextInterface $source
   * @param \Donquixote\OCUI\Text\TextInterface[] $replacements
   */
  public function __construct(TextInterface $source, array $replacements = []) {
    self::validateReplacements(...array_values($replacements));
    $this->source = $source;
    $this->replacements = $replacements;
  }

  private static function validateReplacements(TextInterface ...$args): void {}

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
