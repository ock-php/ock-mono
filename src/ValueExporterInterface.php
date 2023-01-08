<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Donquixote\CodegenTools;

interface ValueExporterInterface {

  /**
   * @param mixed $value
   * @param bool $enclose
   *
   * @return string
   *   PHP expression.
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  public function export(mixed $value, bool $enclose = false): string;

}
