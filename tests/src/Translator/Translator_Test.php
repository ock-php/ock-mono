<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Translator;

use Donquixote\ObCK\Translator\TranslatorInterface;

class Translator_Test implements TranslatorInterface {

  /**
   * {@inheritdoc}
   */
  public function translate(string $source): string {
    return sprintf('<t>%s</t>', $source);
  }

}
