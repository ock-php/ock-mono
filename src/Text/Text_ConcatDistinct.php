<?php

namespace Donquixote\ObCK\Text;

use Donquixote\ObCK\Translator\TranslatorInterface;

class Text_ConcatDistinct implements TextInterface {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private array $sources;

  /**
   * @var string
   */
  private string $glue;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface[] $sources
   * @param string $glue
   */
  public function __construct(array $sources, string $glue) {
    Text::validate(...$sources);
    $this->sources = $sources;
    $this->glue = $glue;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TranslatorInterface $translator): string {
    $parts = [];
    foreach ($this->sources as $id => $source) {
      $parts[$id] = $source->convert($translator);
    }
    return implode($this->glue, array_unique($parts));
  }

}
