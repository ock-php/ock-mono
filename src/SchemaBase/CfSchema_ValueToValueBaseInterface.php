<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaBase;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

/**
 * Base interface for all schema types where the configuration form and summary
 * is the same as for the decorated schema.
 */
interface CfSchema_ValueToValueBaseInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
