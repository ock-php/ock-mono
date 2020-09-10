<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Optionless;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

/**
 * Common base interface for all schemas that are not configurable.
 *
 * A schema class needs to implement one of the child interfaces to actually do
 * something.
 */
interface CfSchema_OptionlessInterface extends CfSchemaInterface {

}
