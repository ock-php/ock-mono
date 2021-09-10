<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Translator;

interface TranslatorInterface {

  /**
   * @param string $source
   *
   * @return string
   */
  public function translate(string $source): string;

}
