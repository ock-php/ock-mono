<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Boolean;

abstract class V2V_Boolean_OrNullBase implements V2V_BooleanInterface {

  /**
   * {@inheritdoc}
   */
  public function getFalseValue() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getFalsePhp(): string {
    return 'NULL';
  }
}
