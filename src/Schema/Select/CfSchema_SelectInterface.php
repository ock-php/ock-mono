<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Select;

use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractSelectInterface;

/**
 * @todo Maybe "Options" should be renamed to "Choice"?
 */
interface CfSchema_SelectInterface extends CfSchema_IdInterface, CfSchemaBase_AbstractSelectInterface {

}
