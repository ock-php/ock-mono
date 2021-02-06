<?php

namespace Donquixote\OCUI\Text;

class Text_Replacements implements TextInterface {

  /**
   * @var \Donquixote\OCUI\Text\TextInterface
   */
  private $original;

  /**
   * @var \Donquixote\OCUI\Text\TextInterface[]
   */
  private $replacements;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Text\TextInterface $original
   * @param \Donquixote\OCUI\Text\TextInterface[] $replacements
   */
  public function __construct(TextInterface $original, array $replacements = []) {
    $this->original = $original;
    $this->replacements = $replacements;
  }

  /**
   * @return \Donquixote\OCUI\Text\TextInterface
   */
  public function getOriginalText(): TextInterface {
    return $this->original;
  }

  /**
   * @return \Donquixote\OCUI\Text\TextInterface[]
   */
  public function getReplacements(): array {
    return $this->replacements;
  }

}
