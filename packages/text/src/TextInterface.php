<?php

declare(strict_types=1);

namespace Ock\Text;

use Ock\Text\Translator\TranslatorInterface;

/**
 * Generic interface for values that can be used as text.
 *
 * Some of these might need specific tools to actually build the text.
 */
interface TextInterface {

  /**
   * Gets translated html.
   *
   * @param \Ock\Text\Translator\TranslatorInterface $translator
   *   Translator to look up strings in another language.
   *
   * @return string
   *   Translated text, safe for html.
   */
  public function convert(TranslatorInterface $translator): string;

}
