<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\ValueProvider;

class CfSchema_ValueProvider_Null implements CfSchema_ValueProviderInterface {

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
