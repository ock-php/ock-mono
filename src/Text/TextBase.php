<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Text;

/**
 * Base class with additional convenience methods.
 */
abstract class TextBase implements TextInterface {

  /**
   * @param string $token
   * @param \Donquixote\ObCK\Text\TextInterface $wrapper
   *
   * @return self
   */
  public function wrap(string $token, TextInterface $wrapper): self {
    return new Text_ReplaceOne($wrapper, $token, $this);
  }

  /**
   * @param string $token
   * @param string $wrapper
   *
   * @return self
   */
  public function wrapT(string $token, string $wrapper): self {
    return $this->wrap($token, Text::t($wrapper));
  }

  /**
   * @param string $token
   * @param string $wrapper
   *
   * @return self
   */
  public function wrapS(string $token, string $wrapper): self {
    return $this->wrap($token, Text::s($wrapper));
  }

  /**
   * @param string $wrapper
   *
   * @return self
   */
  public function wrapSprintf(string $wrapper): self {
    return new Text_Sprintf($wrapper, $this);
  }

}
