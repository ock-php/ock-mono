<?php

declare(strict_types=1);

namespace Drupal\ock\TextToDrupal;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Render\Markup;
use Ock\Text\TextInterface;
use Ock\Text\Translator\TranslatorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(public: true)]
class TextToDrupal_Default implements TextToDrupalInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Text\Translator\TranslatorInterface $translator
   *   String translation service.
   */
  public function __construct(
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
