<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaBase;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

/**
 * Base interface for all schema types where the configuration form and summary
 * is the same as for the decorated schema.
 */
interface CfSchema_ValueToValueBaseInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
