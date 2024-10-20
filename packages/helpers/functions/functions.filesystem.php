<?php

/**
 * @file
 * Functions to deal with file system access.
 */
declare(strict_types=1);

namespace Ock\Helpers;

/**
 * Calls scandir() on a known directory.
 *
 * The "known" here simply means that a failure leads to a runtime exception.
 *
 * @param string $dir
 *   Directory path.
 * @param int $sorting_order
 *   Sorting order.
 *
 * @return list<string>
 *   Directory contents.
 */
function scandir_known(string $dir, int $sorting_order = 0): array {
  try {
    set_error_handler(static function (
      int $error_level,
      string $message,
      string $filename = NULL,
      int $line = NULL,
    ) use ($dir): void {
      throw new \RuntimeException("Scandir failed with '$message' on '$dir'.");
    });
    $result = scandir($dir, $sorting_order);
    if ($result === false) {
      throw new \RuntimeException("Scandir failed on '$dir'.");
    }
    return $result;
  }
  finally {
    restore_error_handler();
  }
}
