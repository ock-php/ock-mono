<?php
declare(strict_types=1);

namespace Donquixote\Cf\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface IdToSchemaInterface {

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id): ?CfSchemaInterface;

}
