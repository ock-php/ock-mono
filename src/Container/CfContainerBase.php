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
use Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormula_Mappers;
use Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelper_Handler;
use Donquixote\OCUI\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelper_Formula;
use Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyid;
use Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmap;
use Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmap_Cache;
use Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormula_Buffer;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormula_Iface;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartial_Callback;
use Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartial_DefmapDrilldown;
use Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartial_IfaceDefmap;
use Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartial_Proxy_Cache;
use Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartial_Proxy_Replacer;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacer_FromPartials;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;
use Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartial_FormulaReplacer;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnything_SmartChain;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Translator\Translator;
use Donquixote\OCUI\Translator\TranslatorInterface;
use Donquixote\OCUI\Util\LocalPackageUtil;
use Donquixote\Containerkit\Container\ContainerBase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class CfContainerBase extends ContainerBase implements CfContainerInterface {

  /**
   * @return \Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface
   *
   * @see $typeToFormula
   */
  protected function get_typeToFormula(): TypeToFormulaInterface {

    $typeToFormula = new TypeToFormula_Iface();
    $typeToFormula = new TypeToFormula_Buffer($typeToFormula);

    return $typeToFormula;
  }

  /**
   * @return \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface
   *
   * @see $schemaToAnything
   *
   * @throws \Donquixote\OCUI\Exception\STABuilderException
   */
  protected function get_schemaToAnything(): FormulaToAnythingInterface {

    $partials = $this->staPartials;

    $partials[] = new FormulaToAnythingPartial_FormulaReplacer(
      $this->schemaReplacer);

    return new FormulaToAnything_SmartChain($partials);
  }

  /**
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   *
   * @see $staPartials
   *
   * @throws \Donquixote\OCUI\Exception\STABuilderException
   */
  protected function get_staPartials(): array {

    return $this->getSTAPartials();
  }

  /**
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
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
   * @return \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface
   *
   * @see $schemaReplacer
   */
  protected function get_schemaReplacer(): FormulaReplacerInterface {

    $partials = $this->getFormulaReplacerPartials();

    return new FormulaReplacer_FromPartials($partials);
  }

  /**
   * @return \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface[]
   */
  protected function getFormulaReplacerPartials(): array {

    $partials = [];

    $partials[] = new FormulaReplacerPartial_IfaceDefmap(
      $this->typeToDefmap,
      TRUE);

    $partials[] = new FormulaReplacerPartial_DefmapDrilldown(
      $this->definitionToFormula,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      TRUE);

    $partials[] = FormulaReplacerPartial_Callback::create($this->logger);

    $partials[] = new FormulaReplacerPartial_Proxy_Cache(
      $this->cacheOrNull);

    $partials[] = new FormulaReplacerPartial_Proxy_Replacer();

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
   * @return \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   *
   * @see $definitionToFormula
   */
  protected function get_definitionToFormula(): DefinitionToFormulaInterface {
    return new DefinitionToFormula_Mappers(
      $this->getDefinitionToFormulaHelpers(),
      $this->logger);
  }

  /**
   * @return \Donquixote\OCUI\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelperInterface[]
   */
  protected function getDefinitionToFormulaHelpers(): array {
    return [
      'schema' => new DefinitionToFormulaHelper_Formula(),
      'handler' => new DefinitionToFormulaHelper_Handler(),
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
