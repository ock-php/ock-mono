<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Translator;

use Donquixote\OCUI\Translator\Lookup\TranslatorLookup_D7;
use Donquixote\OCUI\Util\UtilBase;

final class Translator_D7 extends UtilBase {

  /**
   * @return \Donquixote\OCUI\Translator\TranslatorInterface
   */
  public static function createOrPassthru(): TranslatorInterface {
    return new Translator(TranslatorLookup_D7::createOrPassthru());
  }

  /**
   * @return \Donquixote\OCUI\Translator\TranslatorInterface|null
   */
  public static function create(): ?TranslatorInterface {

    if (NULL === $lookup = TranslatorLookup_D7::create()) {
      return NULL;
    }

    return new Translator($lookup);
  }



}
