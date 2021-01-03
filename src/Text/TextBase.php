<?php

namespace Donquixote\Cf\Text;

abstract class TextBase implements TextInterface {

  /**
   * @var string
   */
  private $value;

  /**
   * @param string $value
   */
  public function __construct(string $value) {
    $this->value = $value;
  }

  /**
   * Gets the unprocessed string value.
   *
   * @return string|null
   */
  public function __toString(): ?string {
    return $this->value;
  }

}
