<?php

declare(strict_types=1);

namespace Ock\Text\Tests;

use Ock\Text\Translator\TranslatorInterface;

class Translator_Testing implements TranslatorInterface {

  /**
   * {@inheritdoc}
   */
  public function translate(string $source): string {
    return sprintf('<t>%s</t>', $source);
  }

}
