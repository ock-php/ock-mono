<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Markup;

use Donquixote\Adaptism\ATA\ATAInterface;
use Donquixote\OCUI\Translatable\TranslatableInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

class Markup_FromTranslatable {

  /**
   * @ATA
   *
   * @param \Donquixote\OCUI\Translatable\TranslatableInterface $translatable
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   *
   * @return \Donquixote\OCUI\Markup\MarkupInterface|null
   */
  public static function create(
    TranslatableInterface $translatable,
    ATAInterface $ata,
    TranslatorInterface $translator
  ): ?MarkupInterface {

    $replacements = [];
    foreach ($translatable->getReplacements() as $k => $v) {

      if (\is_object($v)) {
        $replacement = $ata->adapt($v, MarkupInterface::class);
        if (!$replacement instanceof MarkupInterface) {
          return NULL;
        }
      }
      elseif (\is_string($v)) {
        $replacement = $v;
      }
      else {
        return NULL;
      }

      $replacements[$k] = $replacement;
    }

    $html = $translator->translate(
      $translatable->getOriginalText(),
      $replacements);

    return new Markup($html);
  }

}
