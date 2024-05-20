<?php

declare(strict_types = 1);

namespace Ock\CodegenTools\CodeProcessor;

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
