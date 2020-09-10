<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Optional;

class CfSchema_Optional_Null extends CfSchema_OptionalBase {

  /**
   * {@inheritdoc}
   */
  public function getEmptySummary(): ?string {
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
