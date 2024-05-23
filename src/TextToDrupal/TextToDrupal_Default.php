<?php

declare(strict_types=1);

namespace Drupal\ock\TextToDrupal;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\Translator\TranslatorInterface;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Render\Markup;

#[Service]
class TextToDrupal_Default implements TextToDrupalInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   *   String translation service.
   */
  public function __construct(
    #[GetService]
    private readonly TranslatorInterface $translator,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function convert(TextInterface $text): MarkupInterface {
    $strval = $text->convert($this->translator);
    // @todo Is this string safe or not?
    return Markup::create($strval);
  }

}
