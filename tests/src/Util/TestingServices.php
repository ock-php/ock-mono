<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Tests\Util;

use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionList_Discovery;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Multiple;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\DID\Attribute\Service;
use Donquixote\DID\Container\Container_CTVs;
use Donquixote\DID\Exception\ContainerToValueException;
use Donquixote\DID\Exception\DiscoveryException;
use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\OckPackage;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Donquixote\Ock\Plugin\Registry\PluginRegistry_Discovery;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\Ock\Translator\Translator_Passthru;
use Donquixote\Ock\Translator\TranslatorInterface;
use Psr\Container\ContainerInterface;

class TestingServices {

  /**
   * @return \Psr\Container\ContainerInterface
   *
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  public static function getContainer(): ContainerInterface {
    try {
      return Container_CTVs::fromClassFilesIAs([
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
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   *
   * @throws \ReflectionException
   */
  public static function getServiceDiscoveryLocations(): ClassFilesIAInterface {
    return new ClassFilesIA_Multiple([
      // Discover in object-construction-kit.
      ClassFilesIA::psr4FromClass(FormulaException::class, 1),
      // Discover in object-construction-kit/tests.
      ClassFilesIA::psr4FromClass(self::class),
      // Discover in adaptism.
      ClassFilesIA::psr4FromClass(UniversalAdapterInterface::class, 1),
    ]);
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   * @throws \ReflectionException
   */
  #[Service(serviceIdSuffix: PluginRegistry_Discovery::class)]
  public static function getPluginClassFiles(): ClassFilesIAInterface {
    // Discover in fixtures dir only.
    return ClassFilesIA::psr4FromClass(IntOpInterface::class, 1);
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

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   * @throws \ReflectionException
   */
  #[Service(serviceIdSuffix: AdapterDefinitionList_Discovery::class)]
  public static function getAdapterClassFilesIA(): ClassFilesIAInterface {
    return ClassFilesIA::psr4FromClass(OckPackage::class);
  }

}
