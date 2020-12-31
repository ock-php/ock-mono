<?php

namespace Donquixote\Cf\Text;

class Text_Replacements implements TextInterface {

  /**
   * @var \Donquixote\Cf\Text\TextInterface
   */
  private TextInterface $original;

  /**
   * @var \Donquixote\Cf\Text\TextInterface[]
   */
  private array $replacements;

  /**
   * Constructor.
   *
   * @param \Donquixote\Cf\Text\TextInterface $original
   * @param \Donquixote\Cf\Text\TextInterface[] $replacements
   */
  public function __construct(TextInterface $original, array $replacements = []) {
    $this->original = $original;
    $this->replacements = $replacements;
  }

  /**
   * @return \Donquixote\Cf\Text\TextInterface
   */
  public function getOriginalText() {
    return $this->original;
  }

  /**
   * @return \Donquixote\Cf\Text\TextInterface[]
   */
  public function getReplacements() {
    return $this->replacements;
  }

}
