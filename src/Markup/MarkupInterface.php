<?php
declare(strict_types=1);

namespace Donquixote\Ock\Markup;

interface MarkupInterface {

  /**
   * @return string
   */
  public function __toString(): string;

}
