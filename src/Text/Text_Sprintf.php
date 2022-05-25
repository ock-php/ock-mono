<?php

namespace Donquixote\Ock\Text;

use Donquixote\Ock\Translator\TranslatorInterface;

class Text_Sprintf extends TextBase {

  /**
   * Constructor.
   *
   * @param string $source
   * @param \Donquixote\Ock\Text\TextInterface $replacement
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
