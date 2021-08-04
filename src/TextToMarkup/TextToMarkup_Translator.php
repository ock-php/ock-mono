<?php

declare(strict_types=1);

namespace Donquixote\OCUI\TextToMarkup;

use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

class TextToMarkup_Translator implements TextToMarkupInterface {

  /**
   * @var \Donquixote\OCUI\Translator\TranslatorInterface
   */
  private TranslatorInterface $translator;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   *   Translator.
   */
  public function __construct(TranslatorInterface $translator) {
    $this->translator = $translator;
  }

  /**
   * {@inheritdoc}
   */
  public function textGetMarkup(TextInterface $text): string {
    return $text->convert($this->translator);
  }

}
