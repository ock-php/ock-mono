<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Text;

use Donquixote\ObCK\Translator\TranslatorInterface;

class Text_FluentDecorator extends TextBase {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface
   */
  private TextInterface $decorated;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface $decorated
   */
  public function __construct(TextInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TranslatorInterface $translator): string {
    return $this->decorated->convert($translator);
  }

  /**
   * {@inheritdoc}
   */
  protected function getThis(): TextInterface {
    return $this->decorated;
  }

}
