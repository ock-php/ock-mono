<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionMap;

use Donquixote\OCUI\Defmap\DefinitionsById\DefinitionsByIdInterface;
use Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface;

/**
 * Combination of two interfaces.
 */
interface DefinitionMapInterface extends DefinitionsByIdInterface, IdToDefinitionInterface {

}
