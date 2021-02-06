<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Container;

/**
 * Main cycle of circular dependencies:
 * @property \Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchemaInterface $typeToSchema
 * @property \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $staPartials
 * @property \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
 * @property \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
 * @property \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $schemaReplacer
 * @property \Donquixote\OCUI\Translator\TranslatorInterface $translator
 *
 * Non-circular:
 * @property \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
 * @property \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
 * @property \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
 * @property \Donquixote\OCUI\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
 * @property \Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
 * @property \Donquixote\OCUI\Cache\Prefix\CachePrefixInterface|null $cacheRootOrNull
 *
 * External services
 * @property \Psr\Log\LoggerInterface $logger
 *
 * To be provided by child container:
 * @property \Donquixote\OCUI\Cache\CacheInterface|null $cacheOrNull
 */
interface CfContainerInterface {

}
