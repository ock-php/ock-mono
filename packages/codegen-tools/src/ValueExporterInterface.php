<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Ock\CodegenTools;

interface ValueExporterInterface {

  /**
   * @param mixed $value
   * @param bool $enclose
   *
   * @return string
   *   PHP expression.
   *
   * @throws \Ock\CodegenTools\Exception\CodegenException
   */
  public function export(mixed $value, bool $enclose = false): string;

}
