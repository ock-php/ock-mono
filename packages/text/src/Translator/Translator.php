<?php

declare(strict_types=1);

namespace Ock\Text\Translator;

class Translator {

  /**
   * @return \Ock\Text\Translator\TranslatorInterface
   */
  public static function passthru(): TranslatorInterface {
    return new Translator_Passthru();
  }

}
