<?php

declare(strict_types=1);

namespace Drupal\cu\Formator\Util;

use Donquixote\ObCK\Util\UtilBase;

class ArrayMode extends UtilBase {

  /**
   * Array mode where keys are always numeric and serial.
   *
   * This is NULL, so that calling code does not need to spell out the constant
   * name for the default value.
   */
  const SERIAL = NULL;

  /**
   * Array mode where serial numeric keys and assoc string keys are mixed.
   */
  const MIXED = 1;

  /**
   * Array mode where all keys are treated as associative.
   */
  const ASSOC = 2;

}
