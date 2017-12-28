<?php

namespace Donquixote\ClassDiscovery;

class NsDirUtil {

  /**
   * @param string $directory
   */
  public static function requireUnslashedDirectory(&$directory) {

    if ('/' === substr($directory, -1)) {
      throw new \InvalidArgumentException('Path must be provided without trailing slash or backslash.');
    }
  }

  /**
   * @param string $namespace
   *
   * @return string
   *
   * @throws \InvalidArgumentException
   */
  public static function terminateNamespace($namespace) {

    if ('' === $namespace) {
      return '';
    }

    if ('\\' === substr($namespace, -1)) {
      throw new \InvalidArgumentException('Namespace must be provided without trailing backslash.');
    }

    if ('\\' === $namespace[0]) {
      throw new \InvalidArgumentException('Namespace must be provided without leading backslash.');
    }

    return $namespace . '\\';
  }
}
