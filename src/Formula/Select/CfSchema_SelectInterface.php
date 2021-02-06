<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\SchemaBase\CfSchemaBase_AbstractSelectInterface;

/**
 * @todo Maybe "Options" should be renamed to "Choice"?
 */
interface CfSchema_SelectInterface extends Formula_IdInterface, CfSchemaBase_AbstractSelectInterface {

}
