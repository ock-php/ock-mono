<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Translator;

class Translator {

  /**
   * @return \Donquixote\ObCK\Translator\TranslatorInterface
   */
  public static function passthru(): TranslatorInterface {
    return new Translator_Passthru();
  }

}
