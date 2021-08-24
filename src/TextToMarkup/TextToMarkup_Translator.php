<?php

declare(strict_types=1);

namespace Donquixote\ObCK\TextToMarkup;

use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Translator\TranslatorInterface;

class TextToMarkup_Translator implements TextToMarkupInterface {

  /**
   * @var \Donquixote\ObCK\Translator\TranslatorInterface
   */
  private TranslatorInterface $translator;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Translator\TranslatorInterface $translator
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
