<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\ValueProvider;

class Formula_ValueProvider_Null implements Formula_ValueProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getPhp(): string {
    return 'NULL';
  }
}
