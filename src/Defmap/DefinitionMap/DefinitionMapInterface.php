<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionMap;

use Donquixote\ObCK\Defmap\DefinitionsById\DefinitionsByIdInterface;
use Donquixote\ObCK\Defmap\IdToDefinition\IdToDefinitionInterface;

/**
 * Combination of two interfaces.
 */
interface DefinitionMapInterface extends DefinitionsByIdInterface, IdToDefinitionInterface {

}
