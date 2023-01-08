<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Donquixote\DID\CodegenTools;

interface ValueExporterInterface {

  /**
   * @param mixed $value
   *
   * @return string
   *   PHP expression.
   *
   * @throws \Donquixote\DID\Exception\CodegenException
   */
  public function export(mixed $value, bool $enclose): string;

}
