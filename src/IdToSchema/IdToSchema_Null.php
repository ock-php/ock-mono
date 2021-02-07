<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProvider_Null;
use Donquixote\OCUI\Util\UtilBase;

final class IdToSchema_Null extends UtilBase {

  /**
   * @return \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  public static function create(): IdToSchemaInterface {
    return new IdToSchema_AlwaysTheSame(
      new Formula_ValueProvider_Null());
  }
}
