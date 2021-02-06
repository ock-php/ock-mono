<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Schema\ValueProvider\CfSchema_ValueProvider_Null;
use Donquixote\OCUI\Util\UtilBase;

final class IdToSchema_Null extends UtilBase {

  /**
   * @return \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  public static function create(): IdToSchemaInterface {
    return new IdToSchema_AlwaysTheSame(
      new CfSchema_ValueProvider_Null());
  }
}
