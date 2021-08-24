<?php

namespace Donquixote\ObCK\Text;

use Donquixote\ObCK\Translator\TranslatorInterface;

/**
 * Generic interface for values that can be used as text.
 *
 * Some of these might need specific tools to actually build the text.
 */
interface TextInterface {

  public function convert(TranslatorInterface $translator): string;

}
