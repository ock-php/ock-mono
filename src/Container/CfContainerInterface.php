<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Container;

/**
 * Main cycle of circular dependencies:
 * @property \Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface $typeToFormula
 * @property \Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[] $staPartials
 * @property \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
 * @property \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
 * @property \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $formulaReplacer
 * @property \Donquixote\ObCK\Translator\TranslatorInterface $translator
 *
 * Non-circular:
 * @property \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
 * @property \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
 * @property \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
 * @property \Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
 * @property \Donquixote\ObCK\Defmap\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
 * @property \Donquixote\ObCK\Cache\Prefix\CachePrefixInterface|null $cacheRootOrNull
 *
 * External services
 * @property \Psr\Log\LoggerInterface $logger
 *
 * To be provided by child container:
 * @property \Donquixote\ObCK\Cache\CacheInterface|null $cacheOrNull
 */
interface CfContainerInterface {

}
