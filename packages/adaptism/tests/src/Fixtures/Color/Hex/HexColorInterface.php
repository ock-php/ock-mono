<?php
declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures\Color\Hex;

interface HexColorInterface {

  /**
   * @return string
   *   The 6-char hex representation. Without any leading "#".
   */
  public function getHexCode(): string;

}
