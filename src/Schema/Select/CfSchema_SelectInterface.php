<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Select;

use Donquixote\OCUI\Schema\Id\CfSchema_IdInterface;
use Donquixote\OCUI\SchemaBase\CfSchemaBase_AbstractSelectInterface;

/**
 * @todo Maybe "Options" should be renamed to "Choice"?
 */
interface CfSchema_SelectInterface extends CfSchema_IdInterface, CfSchemaBase_AbstractSelectInterface {

}
