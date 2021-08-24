<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Translator\Lookup;

interface TranslatorLookupInterface {

  /**
   * @param string $string
   *
   * @return string
   */
  public function lookup(string $string): string;

}
