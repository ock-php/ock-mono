<?php
declare(strict_types=1);

namespace Donquixote\Cf\Container;

/**
 * Main cycle of circular dependencies:
 * @property \Donquixote\Cf\Defmap\TypeToSchema\TypeToSchemaInterface $typeToSchema
 * @property \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $staPartials
 * @property \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
 * @property \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
 * @property \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $schemaReplacer
 * @property \Donquixote\Cf\Translator\TranslatorInterface $translator
 *
 * Non-circular:
 * @property \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
 * @property \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
 * @property \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
 * @property \Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
 * @property \Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
 * @property \Donquixote\Cf\Cache\Prefix\CachePrefixInterface|null $cacheRootOrNull
 *
 * External services
 * @property \Psr\Log\LoggerInterface $logger
 *
 * To be provided by child container:
 * @property \Donquixote\Cf\Cache\CacheInterface|null $cacheOrNull
 */
interface CfContainerInterface {

}
