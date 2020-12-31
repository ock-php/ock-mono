<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

interface GeneratorInterface {

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf): string;

}
