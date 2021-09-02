<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Id;

class V2V_Id_Trivial implements V2V_IdInterface {

  /**
   * {@inheritdoc}
   */
  public function idGetPhp($id): string {
    return var_export($id, TRUE);
  }
}
