<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Translator;

interface TranslatorInterface {

  /**
   * @param string $string
   * @param string[] $replacements
   *
   * @return string
   */
  public function translate(string $string, array $replacements = []): string;

}
