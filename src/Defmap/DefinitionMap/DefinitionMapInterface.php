<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionMap;

use Donquixote\Cf\Defmap\DefinitionsById\DefinitionsByIdInterface;
use Donquixote\Cf\Defmap\IdToDefinition\IdToDefinitionInterface;

/**
 * Combination of two interfaces.
 */
interface DefinitionMapInterface extends DefinitionsByIdInterface, IdToDefinitionInterface {

}
