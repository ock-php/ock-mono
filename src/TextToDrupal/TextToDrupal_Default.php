<?php

declare(strict_types=1);

namespace Drupal\cu\TextToDrupal;

use Donquixote\OCUI\Text\TextInterface;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\StringTranslation\TranslationInterface;

class TextToDrupal_Default implements TextToDrupalInterface {

  /**
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  private TranslationInterface $translation;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   String translation service.
   */
  public function __construct(TranslationInterface $translation) {
    $this->translation = $translation;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TextInterface $text): MarkupInterface {
    return $this->translation->translate(__METHOD__);
  }

}
