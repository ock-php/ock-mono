<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Ock\Testing\Diff\DifferInterface;
use Ock\Testing\Diff\ExportedArrayDiffer;
use Ock\Testing\Exporter\Exporter_ToYamlArray;
use Ock\Testing\Exporter\ExporterInterface;
use PHPUnit\Framework\Assert;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\TypedReference;

class Snapshotter_ContainerDefinitions extends AdvancedSnapshotterBase {

  /**
   * {@inheritdoc}
   */
  protected function getItems(): array {
    $container = \Drupal::getContainer();
    Assert::assertInstanceOf(ContainerBuilder::class, $container);
    $definitions = $container->getDefinitions();
    ksort($definitions);
    return $definitions;
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultItemForId(string|int $id): Definition {
    return (new Definition(is_string($id) ? $id : NULL))
      ->setPublic(true)
      ->setAutoconfigured(true)
      ->setAutowired(true);
  }

  /**
   * {@inheritdoc}
   */
  protected function createExporter(): Exporter_ToYamlArray {
    return (new Exporter_ToYamlArray())
      ->withObjectGetters(Definition::class, ['isPrivate()', 'getChanges()'])
      ->withObjectGetters(TaggedIteratorArgument::class)
      ->withDefaultObject((new Definition())
        ->setAutowired(true)
        ->setAutoconfigured(true)
      )
      ->withDefaultObject(new TaggedIteratorArgument('???'))
      ->withDefaultObject(new TypedReference('???', '???'))
      ->withDefaultObject(new Autowire(service: '????'))
      ->withDedicatedExporter(
        Reference::class,
        function (Reference $reference): ?string {
          $id = $reference->__toString();
          $default = new Reference($id);
          /** @noinspection PhpNonStrictObjectEqualityInspection */
          if ($reference == $default) {
            return '@' . $reference;
          }
          return null;
        }
      );
  }

  /**
   * {@inheritdoc}
   */
  protected function createDiffer(): DifferInterface {
    return (new ExportedArrayDiffer)
      ->withIdentifyingProperty(Definition::class, 'getClass()')
      ->withIdentifyingProperty(Definition::class, 'getFactory()')
      ->withNonListProperty(Definition::class, 'getArguments()');
  }

}
