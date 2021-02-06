<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Optionless;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

/**
 * Common base interface for all schemas that are not configurable.
 *
 * A schema class needs to implement one of the child interfaces to actually do
 * something.
 */
interface CfSchema_OptionlessInterface extends CfSchemaInterface {

}
