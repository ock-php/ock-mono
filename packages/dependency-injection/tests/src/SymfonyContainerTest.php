<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests;

use Ock\ClassDiscovery\FactsIA\FactsIA;
use Ock\ClassDiscovery\NamespaceDirectory;
use Ock\ClassDiscovery\Reflection\ClassReflection;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Ock\DependencyInjection\Provider\ServiceProvider;
use Ock\Testing\Exporter\Exporter_ToYamlArray;
use Ock\Testing\RecordedTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use function Ock\Helpers\to_array;

/**
 * Tests the integration with symfony container.
 */
class SymfonyContainerTest extends TestCase {

  use RecordedTestTrait;

  /**
   * Tests the a symfony container using the mechanisms from this package.
   *
   * @dataProvider providerTestSymfonyContainer
   */
  public function testSymfonyContainer(NamespaceDirectory $packageNamespaceDir, string $inspector_file): void {
    $package = $packageNamespaceDir->getReflectionClassesIA();
    $inspector_php_file = $packageNamespaceDir->getDirectory() . '/' . $inspector_file;
    static::assertFileExists($inspector_php_file);
    $inspector_php_file_return = require $inspector_php_file;
    $candidates = [$package, ...to_array($inspector_php_file_return)];
    $container = new ContainerBuilder();
    $this->assertClassesAsRecorded($package, 'classes');
    $factsIA = FactsIA::fromCandidateObjects($candidates);
    $facts = [];
    foreach ($factsIA as $key => $fact) {
      $facts[] = [$key => $fact];
    }
    $this->assertAsRecorded($facts, 'facts', 5);
    $provider = ServiceProvider::fromCandidateObjects($candidates);
    $provider->register($container);
    $this->assertObjectsAsRecorded(
      $container->getDefinitions(),
      'definitions before compile',
      4,
      defaultClass: Definition::class,
    );
    $this->assertObjectsAsRecorded(
      $container->getAliases(),
      'aliases before compile',
      defaultClass: Alias::class,
    );
    $container->compile();
    $ids = $container->getServiceIds();
    $services = \array_map(
      $container->get(...),
      \array_combine($ids, $ids),
    );
    $this->assertObjectsAsRecorded(
      $services,
      'services',
      3,
      arrayKeyIsDefaultClass: true,
    );
  }

  /**
   * Data provider.
   *
   * @return \Iterator<string, array{NamespaceDirectory, string}>
   */
  public function providerTestSymfonyContainer(): \Iterator {
    $fixturesNamespaceDir = NamespaceDirectory::fromKnownClass(self::class)
      ->subdir('Fixtures');
    foreach ($fixturesNamespaceDir->getSubdirsHere() as $subdir_name => $subNamespaceDir) {
      $dir = $subNamespaceDir->getDirectory();
      foreach (scandir($dir) as $candidate) {
        if (\preg_match('#^inspector\.([\w\-]+)\.php$#', $candidate, $m)) {
          [$inspector_file, $inspector_name] = $m;
          $dataset_name = $subdir_name;
          if ($inspector_name !== 'default') {
            $dataset_name .= '.' . $inspector_name;
          }
          yield $dataset_name => [$subNamespaceDir, $inspector_file];
        }
      }
    }
  }

  /**
   * Asserts that classes from a class discovery are as recorded.
   *
   * @param \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $classesIA
   * @param string|null $label
   */
  protected function assertClassesAsRecorded(ReflectionClassesIAInterface $classesIA, string $label = null): void {
    $export = [];
    foreach ($classesIA as $key => $classReflection) {
      static::assertInstanceOf(ClassReflection::class, $classReflection);
      while (isset($export[$key])) {
        // There is no reason to expect duplicate keys, but the test should
        // capture when it happens.
        $key .= '*';
      }
      $export[$key] = $classReflection->getName();
    }
    $this->assertAsRecorded($export, $label);
  }

  /**
   * Asserts that an array of objects is as recorded.
   *
   * @param object[] $objects
   * @param string|null $label
   * @param int $depth
   * @param string|null $defaultClass
   *   Omit the 'class' if identical to the default class.
   * @param bool $arrayKeyIsDefaultClass
   *   Whether to omit the 'class' key, if identical to array key.
   * @param string|null $arrayKeyIsDefaultFor
   *   Result property to omit if identical to array key.
   */
  protected function assertObjectsAsRecorded(
    array $objects,
    string $label = null,
    int $depth = 2,
    string $defaultClass = null,
    bool $arrayKeyIsDefaultClass = false,
    string $arrayKeyIsDefaultFor = null,
  ): void {
    $export = $this->exportForYaml($objects, depth: $depth);
    foreach ($export as $key => $item) {
      if (($item['class'] ?? false) === $defaultClass
        || ($arrayKeyIsDefaultClass && ($item['class'] ?? false) === $key)
      ) {
        unset($export[$key]['class']);
      }
      if ($arrayKeyIsDefaultFor !== null && ($item[$arrayKeyIsDefaultFor] ?? false) === $key) {
        unset($export[$key][$arrayKeyIsDefaultFor]);
      }
    }
    $this->assertAsRecorded($export, $label, $depth + 2);
  }

  /**
   * {@inheritdoc}
   */
  protected function createExporter(): Exporter_ToYamlArray {
    return (new Exporter_ToYamlArray())
      ->withDedicatedExporter(ContainerBuilder::class, fn (
        ContainerBuilder $builder,
      ) => ['class' => \get_class($builder)])
      ->withObjectGetters(Alias::class)
      ->withObjectGetters(Definition::class, ['isPrivate()', 'getChanges()'])
      ->withReferenceObject(new Alias('#'))
      ->withReferenceObjectFactory(
        Definition::class,
        fn (string|int|null $key) => (new Definition(is_string($key) ? $key : null))
          ->setAutoconfigured(true)
          ->setAutowired(true),
      );
  }

}
