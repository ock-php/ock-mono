<?php
declare(strict_types=1);

namespace Donquixote\Cf\Translatable;

class Translatable implements TranslatableInterface {

  /**
   * @var string
   */
  private $original;

  /**
   * @var mixed[]
   */
  private $replacements;

  /**
   * @param string $original
   * @param mixed[] $replacements
   */
  public function __construct($original, array $replacements) {
    $this->original = $original;
    $this->replacements = $replacements;
  }

  /**
   * {@inheritdoc}
   */
  public function getOriginalText(): string {
    return $this->original;
  }

  /**
   * {@inheritdoc}
   */
  public function getReplacements(): array {
    return $this->replacements;
  }
}
