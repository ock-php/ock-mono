<?php
declare(strict_types=1);

namespace Drupal\cu\Util;

/**
 * Base class for classes that cannot be instantiated, e.g. because they only
 * hold static methods.
 *
 * Typically, subclasses will also be marked as final.
 */
abstract class UtilBase {

  private function __construct() {}
}
