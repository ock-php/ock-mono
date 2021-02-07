<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Optional;

use Donquixote\OCUI\Text\TextInterface;

class Formula_Optional_Null extends Formula_OptionalBase {

  /**
   * {@inheritdoc}
   */
  public function getEmptySummary(): ?TextInterface {
    return NULL;
  }

  /**
   * @return mixed
   *   Typically NULL.
   */
  final public function getEmptyValue() {
    return NULL;
  }

  /**
   * @return string
   *   Typically 'NULL'.
   */
  final public function getEmptyPhp(): string {
    return 'NULL';
  }
}
