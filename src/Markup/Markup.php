<?php
declare(strict_types=1);

namespace Donquixote\Cf\Markup;

class Markup implements MarkupInterface {

  /**
   * @var string
   */
  private $html;

  /**
   * @param string $html
   */
  public function __construct($html) {
    $this->html = $html;
  }

  /**
   * @return string
   */
  public function __toString(): string {
    return $this->html;
  }
}
