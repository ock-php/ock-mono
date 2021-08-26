<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything\Partial;

abstract class FormulaToAnythingPartialZeroBase implements FormulaToAnythingPartialInterface {

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }
}
