<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Container;

use Donquixote\ReflectionKit\ParamToValue\ParamToValue_ObjectsMatchType;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\OCUI\Cache\CacheInterface;
use Donquixote\OCUI\Cache\Prefix\CachePrefix_Root;
use Donquixote\OCUI\Cache\Prefix\CachePrefixInterface;
use Donquixote\OCUI\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndId_Cache;
use Donquixote\OCUI\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;
use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabel;
use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\OCUI\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelper_Handler;
use Donquixote\OCUI\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelper_Schema;
use Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyid;
use Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmap;
use Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmap_Cache;
use Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface;
use Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchema_Buffer;
use Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchema_Iface;
use Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchemaInterface;
use Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartial_Callback;
use Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartial_DefmapDrilldown;
use Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartial_IfaceDefmap;
use Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartial_Proxy_Cache;
use Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartial_Proxy_Replacer;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacer_FromPartials;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;
use Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartial_SchemaReplacer;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnything_SmartChain;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Translator\Translator;
use Donquixote\OCUI\Translator\TranslatorInterface;
use Donquixote\OCUI\Util\LocalPackageUtil;
use Donquixote\Containerkit\Container\ContainerBase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class CfContainerBase extends ContainerBase implements CfContainerInterface {

  /**
   * @return \Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchemaInterface
   *
   * @see $typeToSchema
   */
  protected function get_typeToSchema(): TypeToSchemaInterface {

    $typeToSchema = new TypeToSchema_Iface();
    $typeToSchema = new TypeToSchema_Buffer($typeToSchema);

    return $typeToSchema;
  }

  /**
   * @return \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface
   *
   * @see $schemaToAnything
   *
   * @throws \Donquixote\OCUI\Exception\STABuilderException
   */
  protected function get_schemaToAnything(): SchemaToAnythingInterface {

    $partials = $this->staPartials;

    $partials[] = new SchemaToAnythingPartial_SchemaReplacer(
      $this->schemaReplacer);

    return new SchemaToAnything_SmartChain($partials);
  }

  /**
   * @return \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   *
   * @see $staPartials
   *
   * @throws \Donquixote\OCUI\Exception\STABuilderException
   */
  protected function get_staPartials(): array {

    return $this->getSTAPartials();
  }

  /**
   * @return \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   *
   * @throws \Donquixote\OCUI\Exception\STABuilderException
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
   * @return \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface
   *
   * @see $schemaReplacer
   */
  protected function get_schemaReplacer(): SchemaReplacerInterface {

    $partials = $this->getSchemaReplacerPartials();

    return new SchemaReplacer_FromPartials($partials);
  }

  /**
   * @return \Donquixote\OCUI\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
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
   * @return \Donquixote\OCUI\Translator\TranslatorInterface
   *
   * @see $translator
   */
  protected function get_translator(): TranslatorInterface {
    return Translator::createPassthru();
  }

  /**
   * @return \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   *
   * @see $definitionToSchema
   */
  protected function get_definitionToSchema(): DefinitionToSchemaInterface {
    return new DefinitionToSchema_Mappers(
      $this->getDefinitionToSchemaHelpers(),
      $this->logger);
  }

  /**
   * @return \Donquixote\OCUI\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelperInterface[]
   */
  protected function getDefinitionToSchemaHelpers(): array {
    return [
      'schema' => new DefinitionToSchemaHelper_Schema(),
      'handler' => new DefinitionToSchemaHelper_Handler(),
    ];
  }

  /**
   * @return \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToLabel
   */
  protected function get_definitionToLabel(): DefinitionToLabelInterface {
    return DefinitionToLabel::create();
  }

  /**
   * @return \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToGrouplabel
   */
  protected function get_definitionToGrouplabel(): DefinitionToLabelInterface {
    return DefinitionToLabel::createGroupLabel();
  }

  /**
   * @return \Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface
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
   * @return \Donquixote\OCUI\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
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
   * @return \Donquixote\OCUI\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
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
   * @return \Donquixote\OCUI\Cache\Prefix\CachePrefixInterface|null
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
   * @return \Donquixote\OCUI\Cache\CacheInterface|null
   *
   * @see $cacheOrNull
   */
  protected function get_cacheOrNull(): ?CacheInterface   {
    return NULL;
  }

}
