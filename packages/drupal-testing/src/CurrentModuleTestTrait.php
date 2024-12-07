<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting;

/**
 * A test where one specific module is in the focus.
 */
trait CurrentModuleTestTrait {

  /**
   * Gets the module name the services of which to test.
   *
   * This base implementation uses a crude heuristic, and should be replaced
   * with a custom implementation if the heuristic does not work.
   *
   * @return string
   *   Module name.
   */
  protected function getTestedModuleName(): string {
    if (!preg_match('@^Drupal\\\\Tests\\\\(\w+)\\\\@', static::class, $matches)) {
      throw new \RuntimeException(sprintf('Class name %s does not imply a module name.', static::class));
    }
    return $matches[1];
  }

}
