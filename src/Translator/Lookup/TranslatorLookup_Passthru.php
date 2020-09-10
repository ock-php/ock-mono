<?php
declare(strict_types=1);

namespace Donquixote\Cf\Translator\Lookup;

class TranslatorLookup_Passthru implements TranslatorLookupInterface {

  /**
   * {@inheritdoc}
   */
  public function lookup(string $string): string {
    return $string;
  }
}
