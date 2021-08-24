<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Container;

use Donquixote\ReflectionKit\ParamToValue\ParamToValue_ObjectsMatchType;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\ObCK\Cache\CacheInterface;
use Donquixote\ObCK\Cache\Prefix\CachePrefix_Root;
use Donquixote\ObCK\Cache\Prefix\CachePrefixInterface;
use Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndId_Cache;
use Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;
use Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabel;
use Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormula_Mappers;
use Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\ObCK\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelper_Handler;
use Donquixote\ObCK\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelper_Formula;
use Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyid;
use Donquixote\ObCK\Defmap\TypeToDefmap\TypeToDefmap;
use Donquixote\ObCK\Defmap\TypeToDefmap\TypeToDefmap_Cache;
use Donquixote\ObCK\Defmap\TypeToDefmap\TypeToDefmapInterface;
use Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormula_Buffer;
use Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormula_Iface;
use Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\ObCK\FormulaReplacer\Partial\FormulaReplacerPartial_Callback;
use Donquixote\ObCK\FormulaReplacer\Partial\FormulaReplacerPartial_DefmapDrilldown;
use Donquixote\ObCK\FormulaReplacer\Partial\FormulaReplacerPartial_IfaceDefmap;
use Donquixote\ObCK\FormulaReplacer\Partial\FormulaReplacerPartial_Proxy_Cache;
use Donquixote\ObCK\FormulaReplacer\Partial\FormulaReplacerPartial_Proxy_Replacer;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacer_FromPartials;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;
use Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartial_FormulaReplacer;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnything_SmartChain;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Translator\Translator;
use Donquixote\ObCK\Translator\TranslatorInterface;
use Donquixote\ObCK\Util\LocalPackageUtil;
use Donquixote\Containerkit\Container\ContainerBase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class CfContainerBase extends ContainerBase implements CfContainerInterface {

  /**
   * @return \Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface
   *
   * @see $typeToFormula
   */
  protected function get_typeToFormula(): TypeToFormulaInterface {

    $typeToFormula = new TypeToFormula_Iface();
    $typeToFormula = new TypeToFormula_Buffer($typeToFormula);

    return $typeToFormula;
  }

  /**
   * @return \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface
   *
   * @see $formulaToAnything
   *
   * @throws \Donquixote\ObCK\Exception\STABuilderException
   */
  protected function get_formulaToAnything(): FormulaToAnythingInterface {

    $partials = $this->staPartials;

    $partials[] = new FormulaToAnythingPartial_FormulaReplacer(
      $this->formulaReplacer);

    return new FormulaToAnything_SmartChain($partials);
  }

  /**
   * @return \Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   *
   * @see $staPartials
   *
   * @throws \Donquixote\ObCK\Exception\STABuilderException
   */
  protected function get_staPartials(): array {

    return $this->getSTAPartials();
  }

  /**
   * @return \Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[]
   *
   * @throws \Donquixote\ObCK\Exception\STABuilderException
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
   * @return \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface
   *
   * @see $formulaReplacer
   */
  protected function get_formulaReplacer(): FormulaReplacerInterface {

    $partials = $this->getFormulaReplacerPartials();

    return new FormulaReplacer_FromPartials($partials);
  }

  /**
   * @return \Donquixote\ObCK\FormulaReplacer\Partial\FormulaReplacerPartialInterface[]
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
   * @return \Donquixote\ObCK\Translator\TranslatorInterface
   *
   * @see $translator
   */
  protected function get_translator(): TranslatorInterface {
    return Translator::createPassthru();
  }

  /**
   * @return \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   *
   * @see $definitionToFormula
   */
  protected function get_definitionToFormula(): DefinitionToFormulaInterface {
    return new DefinitionToFormula_Mappers(
      $this->getDefinitionToFormulaHelpers(),
      $this->logger);
  }

  /**
   * @return \Donquixote\ObCK\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelperInterface[]
   */
  protected function getDefinitionToFormulaHelpers(): array {
    return [
      'formula' => new DefinitionToFormulaHelper_Formula(),
      'handler' => new DefinitionToFormulaHelper_Handler(),
    ];
  }

  /**
   * @return \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToLabel
   */
  protected function get_definitionToLabel(): DefinitionToLabelInterface {
    return DefinitionToLabel::create();
  }

  /**
   * @return \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToGrouplabel
   */
  protected function get_definitionToGrouplabel(): DefinitionToLabelInterface {
    return DefinitionToLabel::createGroupLabel();
  }

  /**
   * @return \Donquixote\ObCK\Defmap\TypeToDefmap\TypeToDefmapInterface
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
   * @return \Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
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
   * @return \Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
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
   * @return \Donquixote\ObCK\Cache\Prefix\CachePrefixInterface|null
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
   * @return \Donquixote\ObCK\Cache\CacheInterface|null
   *
   * @see $cacheOrNull
   */
  protected function get_cacheOrNull(): ?CacheInterface   {
    return NULL;
  }

}
