<?php

declare(strict_types=1);

namespace Ock\Text;

use Ock\Text\Translator\TranslatorInterface;

class Text_SprintfOne extends TextBase {

  /**
   * Constructor.
   *
   * @param string $source
   * @param \Ock\Text\TextInterface $replacement
   */
  public function __construct(
    private readonly string $source,
    private readonly TextInterface $replacement,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function convert(TranslatorInterface $translator): string {
    return sprintf(
      $this->source,
      $this->replacement->convert($translator),
    );
  }

}
