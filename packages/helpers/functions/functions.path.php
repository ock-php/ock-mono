<?php

/**
 * @file
 * Functions to deal with paths.
 */
declare(strict_types=1);

namespace Ock\Helpers;

use Composer\Composer;

/**
 * Gets the root path of the current project.
 *
 * This relies on composer/composer package being installed in the right place.
 *
 * @return string
 *   Root path.
 */
function project_root_path(): string {
  try {
    $class_path = (new \ReflectionClass(Composer::class))->getFileName();
  }
  catch (\ReflectionException $e) {
    throw new \RuntimeException(sprintf('Class %s not found. Perhaps an unknown version of Composer is used, or the class loader is not initialized.', Composer::class));
  }
  if (!preg_match(
    '@^(/.*)/vendor/composer/composer/src/Composer/Composer\.php$@',
    $class_path,
    $matches,
  )) {
    throw new \RuntimeException(sprintf("The `composer/composer` package is installed in an unexpected location '%s'", $class_path));
  }
  return $matches[1];
}
