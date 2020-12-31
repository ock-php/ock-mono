<?php

namespace Donquixote\Cf\FlaggedString;

/**
 * Default implementation, behaving like a value object.
 */
class FlaggedString implements FlaggedStringInterface {

  /**
   * @var string
   */
  private string $string;

  /**
   * @var string[]
   */
  private array $flags;

  /**
   * Constructor.
   *
   * @param string $string
   *   The string.
   * @param string[] $flag_names
   *   Names of all the flags.
   */
  public function __construct(string $string, array $flag_names = []) {
    $this->string = $string;
    $this->flags = array_fill_keys($flag_names, TRUE);
  }

  /**
   * Immutable setter. Adds or unsets a flag.
   *
   * @param string $flag_name
   *   Name of the flag to set.
   * @param bool $value
   *   TRUE, to set the flag, FALSE to unset it.
   *
   * @return static
   *   Modified clone.
   */
  public function with(string $flag_name, bool $value = TRUE) {
    $clone = clone $this;
    if ($value) {
      $clone->flags[$flag_name] = TRUE;
    }
    else {
      unset($clone->flags[$flag_name]);
    }
    return $clone;
  }

  /**
   * Immutable setter. Unsets a flag.
   *
   * This is a shortcut.
   *
   * @param string $flag_name
   *   Name of the flag to set.
   *
   * @return static
   *   Modified clone.
   */
  public function without(string $flag_name) {
    $clone = clone $this;
    unset($clone->flags[$flag_name]);
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function is(string $flag_name): bool {
    return !empty($this->flags[$flag_name]);
  }

  /**
   * {@inheritdoc }
   */
  public function __toString(): string {
    return $this->string;
  }

}
