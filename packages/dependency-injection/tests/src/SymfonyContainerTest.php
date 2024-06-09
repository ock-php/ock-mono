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
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
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
    $services = [];
    foreach ($ids as $id) {
      $this->assertTrue($container->has($id));
      try {
        $services[$id] = $container->get($id);
      }
      catch (ServiceNotFoundException $e) {
        if ($e->getId() === $id) {
          $services[$id] = '(not found)';
        }
        else {
          throw $e;
        }
      }
    }
    $this->assertObjectsAsRecorded(
      $services,
      'services',
      7,
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
