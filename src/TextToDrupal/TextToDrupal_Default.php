<?php

declare(strict_types=1);

namespace Drupal\ock\TextToDrupal;

use Donquixote\Ock\Text\TextInterface;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\StringTranslation\TranslationInterface;

class TextToDrupal_Default implements TextToDrupalInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   String translation service.
   */
  public function __construct(
    private readonly TranslationInterface $translation,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function convert(TextInterface $text): MarkupInterface {
    return $this->translation->translate(__METHOD__);
  }

}
