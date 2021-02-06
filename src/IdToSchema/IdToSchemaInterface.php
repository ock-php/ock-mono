<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

interface IdToSchemaInterface {

  /**
   * @param string $id
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface|null
   */
  public function idGetSchema(string $id): ?CfSchemaInterface;

}
