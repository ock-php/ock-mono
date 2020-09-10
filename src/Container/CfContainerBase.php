<?php
declare(strict_types=1);

namespace Donquixote\Cf\Container;

use Donquixote\ReflectionKit\ParamToValue\ParamToValue_ObjectsMatchType;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Cache\CacheInterface;
use Donquixote\Cf\Cache\Prefix\CachePrefix_Root;
use Donquixote\Cf\Cache\Prefix\CachePrefixInterface;
use Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndId_Cache;
use Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;
use Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabel;
use Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelper_Handler;
use Donquixote\Cf\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelper_Schema;
use Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyid;
use Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmap;
use Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmap_Cache;
use Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmapInterface;
use Donquixote\Cf\Defmap\TypeToSchema\TypeToSchema_Buffer;
use Donquixote\Cf\Defmap\TypeToSchema\TypeToSchema_Iface;
use Donquixote\Cf\Defmap\TypeToSchema\TypeToSchemaInterface;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_Callback;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_DefmapDrilldown;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_IfaceDefmap;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_Proxy_Cache;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_Proxy_Replacer;
use Donquixote\Cf\SchemaReplacer\SchemaReplacer_FromPartials;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_SchemaReplacer;
use Donquixote\Cf\SchemaToAnything\SchemaToAnything_SmartChain;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\Translator;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\LocalPackageUtil;
use Donquixote\Containerkit\Container\ContainerBase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class CfContainerBase extends ContainerBase implements CfContainerInterface {

  /**
   * @return \Donquixote\Cf\Defmap\TypeToSchema\TypeToSchemaInterface
   *
   * @see $typeToSchema
   */
  protected function get_typeToSchema(): TypeToSchemaInterface {

    $typeToSchema = new TypeToSchema_Iface();
    $typeToSchema = new TypeToSchema_Buffer($typeToSchema);

    return $typeToSchema;
  }

  /**
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   *
   * @see $schemaToAnything
   *
   * @throws \Donquixote\Cf\Exception\STABuilderException
   */
  protected function get_schemaToAnything(): SchemaToAnythingInterface {

    $partials = $this->staPartials;

    $partials[] = new SchemaToAnythingPartial_SchemaReplacer(
      $this->schemaReplacer);

    return new SchemaToAnything_SmartChain($partials);
  }

  /**
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   *
   * @see $staPartials
   *
   * @throws \Donquixote\Cf\Exception\STABuilderException
   */
  protected function get_staPartials(): array {

    return $this->getSTAPartials();
  }

  /**
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   *
   * @throws \Donquixote\Cf\Exception\STABuilderException
   */
  protected function getSTAPartials(): array {

    return LocalPackageUtil::collectSTAPartials($this->paramToValue);
  }

  /**
   * @return \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface
   *
   * @see $paramToValue
   */
  protected function get_paramToValue(): ParamToValueInterface {

    return new ParamToValue_ObjectsMatchType(
      [
        $this->translator,
      ]);
  }

  /**
   * @return \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface
   *
   * @see $schemaReplacer
   */
  protected function get_schemaReplacer(): SchemaReplacerInterface {

    $partials = $this->getSchemaReplacerPartials();

    return new SchemaReplacer_FromPartials($partials);
  }

  /**
   * @return \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
   */
  protected function getSchemaReplacerPartials(): array {

    $partials = [];

    $partials[] = new SchemaReplacerPartial_IfaceDefmap(
      $this->typeToDefmap,
      TRUE);

    $partials[] = new SchemaReplacerPartial_DefmapDrilldown(
      $this->definitionToSchema,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      TRUE);

    $partials[] = SchemaReplacerPartial_Callback::create($this->logger);

    $partials[] = new SchemaReplacerPartial_Proxy_Cache(
      $this->cacheOrNull);

    $partials[] = new SchemaReplacerPartial_Proxy_Replacer();

    return $partials;
  }

  /**
   * @return \Donquixote\Cf\Translator\TranslatorInterface
   *
   * @see $translator
   */
  protected function get_translator(): TranslatorInterface {
    return Translator::createPassthru();
  }

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   *
   * @see $definitionToSchema
   */
  protected function get_definitionToSchema(): DefinitionToSchemaInterface {
    return new DefinitionToSchema_Mappers(
      $this->getDefinitionToSchemaHelpers(),
      $this->logger);
  }

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelperInterface[]
   */
  protected function getDefinitionToSchemaHelpers(): array {
    return [
      'schema' => new DefinitionToSchemaHelper_Schema(),
      'handler' => new DefinitionToSchemaHelper_Handler(),
    ];
  }

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToLabel
   */
  protected function get_definitionToLabel(): DefinitionToLabelInterface {
    return DefinitionToLabel::create();
  }

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToGrouplabel
   */
  protected function get_definitionToGrouplabel(): DefinitionToLabelInterface {
    return DefinitionToLabel::createGroupLabel();
  }

  /**
   * @return \Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmapInterface
   *
   * @see $typeToDefmap
   */
  protected function get_typeToDefmap(): TypeToDefmapInterface {

    $typeToDefinitionsById = new TypeToDefinitionsbyid(
      $this->definitionsByTypeAndId);

    if (NULL !== $cacheRoot = $this->cacheRootOrNull) {
      return new TypeToDefmap($typeToDefinitionsById);
    }

    return new TypeToDefmap_Cache(
      $typeToDefinitionsById,
      $cacheRoot->withAppendedPrefix(
        $this->getDefinitionsCachePrefix()));
  }

  /**
   * @return string
   */
  protected function getDefinitionsCachePrefix(): string {
    return 'definitions:';
  }

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   *
   * @see $definitionsByTypeAndId
   */
  protected function get_definitionsByTypeAndId(): DefinitionsByTypeAndIdInterface {

    $definitionsByTypeAndId = $this->getDefinitionDiscovery();

    if (NULL === $cacheRoot = $this->cacheRootOrNull) {
      return $definitionsByTypeAndId;
    }

    return new DefinitionsByTypeAndId_Cache(
      $definitionsByTypeAndId,
      $cacheRoot->getOffset(
        $this->getDefinitionsCacheKey()));
  }

  /**
   * @return string
   */
  protected function getDefinitionsCacheKey(): string {
    return 'definitions-all:';
  }

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  abstract protected function getDefinitionDiscovery(): DefinitionsByTypeAndIdInterface;

  /**
   * Gets a logger. This should be overwritten!
   *
   * @return \Psr\Log\LoggerInterface
   *
   * @see $logger
   */
  protected function get_logger(): LoggerInterface {
    return new NullLogger();
  }

  /**
   * @return \Donquixote\Cf\Cache\Prefix\CachePrefixInterface|null
   *
   * @see $cacheRootOrNull
   */
  protected function get_cacheRootOrNull(): ?CachePrefixInterface {

    if (NULL === ($cache = $this->cacheOrNull)) {
      return NULL;
    }

    $root = new CachePrefix_Root($cache);

    if ('' === $prefix = $this->getCachePrefix()) {
      return $root;
    }

    return $root->withAppendedPrefix($prefix);
  }

  /**
   * @return string
   */
  protected function getCachePrefix(): string {
    return '';
  }

  /**
   * @return \Donquixote\Cf\Cache\CacheInterface|null
   *
   * @see $cacheOrNull
   */
  protected function get_cacheOrNull(): ?CacheInterface   {
    return NULL;
  }

}
