<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

namespace Donquixote\Ock\Tests;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\Ock\FormulaAdapter;
use Donquixote\Ock\ParamToLabel\ParamToLabel;
use Donquixote\Ock\ParamToLabel\ParamToLabelInterface;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Donquixote\Ock\Plugin\Map\PluginMap_Registry;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;
use Donquixote\Ock\Plugin\Registry\PluginRegistry_Discovery;
use Donquixote\Ock\Plugin\Registry\PluginRegistryInterface;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\Ock\Translator\Translator_Passthru;
use Donquixote\Ock\Translator\TranslatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FormulaTestBase extends TestCase {

  /**
   * Gets a universal adapter to convert formulas.
   *
   * @param object[] $objects
   *   Objects to pass to the constructors.
   *
   * @return \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface
   *   The object.
   */
  protected function getAdapter(array $objects = []): UniversalAdapterInterface {
    $objects[ParamToLabelInterface::class] = new ParamToLabel();
    # $objects[] = $logger;
    $objects[PluginMapInterface::class] = $this->getPluginMap();
    $objects[PluginGroupLabelsInterface::class] = new PluginGroupLabels([]);
    $objects[TranslatorInterface::class] = new Translator_Passthru();
    $container = $this->fixedObjectsContainer($objects);
    /** @noinspection PhpUnhandledExceptionInspection */
    return UniversalAdapter::fromClassFilesIA(
      ClassFilesIA::psr4FromClass(FormulaAdapter::class),
      $container,
    );
  }

  protected function fixedObjectsContainer(array $objects): ContainerInterface {
    return new class($objects) implements ContainerInterface {
      public function __construct(
        private readonly array $objects,
      ) {}

      public function get(string $id) {
        if (!isset($this->objects[$id])) {
          throw new class(sprintf(
            'Service %s not found.',
            $id,
          )) extends \Exception implements NotFoundExceptionInterface {};
        }
        return $this->objects[$id];
      }

      public function has(string $id): bool {
        return isset($this->objects[$id]);
      }
    };
  }

  /**
   * @return \Donquixote\Ock\Plugin\Map\PluginMap_Registry
   */
  protected function getPluginMap(): PluginMapInterface {
    return new PluginMap_Registry($this->getPluginRegistry());
  }

  /**
   * @return \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface
   */
  protected function getPluginRegistry(): PluginRegistryInterface {
    $classFilesIA = ClassFilesIA::psr4FromClass(IntOpInterface::class, 1);
    return PluginRegistry_Discovery::fromClassFilesIA($classFilesIA);
  }

  /**
   * Data provider.
   *
   * @return \Iterator<array{string}>
   *   Parameter combos.
   */
  public function providerTestFormula(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures/formula';
    $candidates = scandir($dir);
    foreach ($candidates as $candidate) {
      if (preg_match('@^(\w+)\.php$@', $candidate, $m)) {
        yield [$m[1]];
      }
    }
  }

  /**
   * Data provider.
   *
   * @return \Iterator<array{string, string}>
   *   Parameter combos.
   */
  public function providerTestFormulaCases(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures/formula';
    $candidates = scandir($dir);
    // Prevent duplicates, but detect orphan files.
    $comboss_map = [];
    foreach ($candidates as $candidate) {
      if (preg_match('@^(\w+)\.(\w+)\.\w+$@', $candidate, $m)) {
        [, $base, $case] = $m;
        $comboss_map[$base][$case] = TRUE;
      }
    }
    foreach ($comboss_map as $base => $cases_map) {
      /** @psalm-suppress RedundantCast */
      $base = (string) $base;
      foreach ($cases_map as $case => $_) {
        /** @psalm-suppress RedundantCast */
        yield [$base, (string) $case];
      }
    }
  }

  /**
   * Data provider.
   *
   * @return \Iterator<int, array{string, string}>
   *   Argument combos.
   */
  public function providerTestIface(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures/iface';
    foreach (scandir($dir) as $dir_candidate) {
      if ($dir_candidate === '.' || $dir_candidate === '..') {
        continue;
      }
      // Prevent duplicates, but detect orphan files.
      $names_map = [];
      foreach (scandir($dir . '/' . $dir_candidate) as $file_candidate) {
        if (preg_match('@^(\w+)\.\w+$@', $file_candidate, $m)) {
          $names_map[$m[1]] = TRUE;
        }
      }
      foreach ($names_map as $name => $_) {
        yield [$dir_candidate, $name];
      }
    }
  }

}
