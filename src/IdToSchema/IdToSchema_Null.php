<?php
declare(strict_types=1);

namespace Donquixote\Cf\IdToSchema;

use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_Null;
use Donquixote\Cf\Util\UtilBase;

final class IdToSchema_Null extends UtilBase {

  /**
   * @return \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  public static function create(): IdToSchemaInterface {
    return new IdToSchema_AlwaysTheSame(
      new CfSchema_ValueProvider_Null());
  }
}
