<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Optional;

use Donquixote\OCUI\Text\TextInterface;

class CfSchema_Optional_Null extends CfSchema_OptionalBase {

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
