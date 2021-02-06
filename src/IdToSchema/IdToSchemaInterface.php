<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

interface IdToSchemaInterface {

  /**
   * @param string $id
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface|null
   */
  public function idGetSchema(string $id): ?CfSchemaInterface;

}
