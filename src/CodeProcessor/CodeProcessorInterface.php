<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools\CodeProcessor;

interface CodeProcessorInterface {

  /**
   * @param string $php
   *   Original php code.
   *
   * @return string
   *   Processed php code.
   */
  public function process(string $php): string;

}
