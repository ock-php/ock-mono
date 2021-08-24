<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Translator;

use Donquixote\ObCK\Translator\Lookup\TranslatorLookup_D7;
use Donquixote\ObCK\Util\UtilBase;

final class Translator_D7 extends UtilBase {

  /**
   * @return \Donquixote\ObCK\Translator\TranslatorInterface
   */
  public static function createOrPassthru(): TranslatorInterface {
    return new Translator(TranslatorLookup_D7::createOrPassthru());
  }

  /**
   * @return \Donquixote\ObCK\Translator\TranslatorInterface|null
   */
  public static function create(): ?TranslatorInterface {

    if (NULL === $lookup = TranslatorLookup_D7::create()) {
      return NULL;
    }

    return new Translator($lookup);
  }



}
