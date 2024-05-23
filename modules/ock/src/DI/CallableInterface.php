<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Drupal\ock\DI;

/**
 * @template T as object
 */
interface CallableInterface {

  /**
   * @param mixed ...$args
   *
   * @return T
   */
  public function __invoke(...$args): object;

}
