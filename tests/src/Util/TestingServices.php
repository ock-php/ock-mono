<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Tests\Util;

use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionList_Discovery;
use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface;
use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\Attribute\Service;
use Donquixote\Adaptism\DI\DefaultContainer;
use Donquixote\Adaptism\Exception\ContainerToValueException;
use Donquixote\Adaptism\Exception\DiscoveryException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Donquixote\Ock\Plugin\Registry\PluginRegistry_Discovery;
use Donquixote\Ock\Plugin\Registry\PluginRegistryInterface;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\Ock\Translator\Translator_Passthru;
use Donquixote\Ock\Translator\TranslatorInterface;
use Psr\Container\ContainerInterface;

class TestingServices {

  /**
   * @return \Psr\Container\ContainerInterface
   *
   * @throws \Donquixote\Adaptism\Exception\ContainerToValueException
   */
  public static function getContainer(): ContainerInterface {
    try {
      return DefaultContainer::fromClassFilesIAs([
        // Discover in object-construction-kit.
        ClassFilesIA::psr4FromClass(FormulaException::class, 1),
        // Discover in object-construction-kit/tests.
        ClassFilesIA::psr4FromClass(self::class),
        // Discover in adaptism.
        ClassFilesIA::psr4FromClass(UniversalAdapterInterface::class, 1),
      ]);
    }
    catch (\ReflectionException|DiscoveryException $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
  }

  /**
   * @return \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface
   *
   * @throws \ReflectionException
   */
  #[Service]
  public static function getPluginRegistry(): PluginRegistryInterface {
    $classFilesIA = ClassFilesIA::psr4FromClass(IntOpInterface::class, 1);
    return PluginRegistry_Discovery::fromClassFilesIA($classFilesIA);
  }

  /**
   * @return \Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface
   */
  #[Service]
  public static function getPluginGroupLabels(): PluginGroupLabelsInterface {
    return new PluginGroupLabels([]);
  }

  /**
   * @return \Donquixote\Ock\Translator\TranslatorInterface
   */
  #[Service]
  public static function getTranslator(): TranslatorInterface {
    return new Translator_Passthru();
  }

  #[Service]
  public static function getAdapterDefinitionList(
    #[GetService]
    AdapterDefinitionList_Discovery $emptyDefinitionList,
  ): AdapterDefinitionListInterface {
    $classFilesIA = ClassFilesIA::psr4FromClass(FormulaException::class, 1);
    return $emptyDefinitionList->withClassFilesIA($classFilesIA);
  }

}
