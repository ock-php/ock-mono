<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Markup;

class Markup implements MarkupInterface {

  /**
   * @var string
   */
  private $html;

  /**
   * @param string $html
   */
  public function __construct(string $html) {
    $this->html = $html;
  }

  /**
   * @return string
   */
  public function __toString(): string {
    return $this->html;
  }
}
