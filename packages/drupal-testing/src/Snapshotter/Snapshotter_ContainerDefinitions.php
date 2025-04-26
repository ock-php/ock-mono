<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Ock\Testing\Diff\DifferInterface;
use Ock\Testing\Diff\ExportedArrayDiffer;
use Ock\Testing\Exporter\Exporter_ToYamlArray;
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
  protected function createExporter(bool $top_level): Exporter_ToYamlArray {
    $exporter = (new Exporter_ToYamlArray())
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
    if (!$top_level) {
      return $exporter;
    }
    return $exporter
      // Remove information from event subscribers, it causes too much noise.
      ->withResultFilter(function (array $result, mixed $definitions) use ($top_level): array {
        foreach ($result['event_dispatcher']['getMethodCalls()'] ?? [] as $i => $call_result) {
          if ($call_result[0] === 'addListener') {
            unset($result['event_dispatcher']['getMethodCalls()'][$i]);
          }
        }
        foreach ($result as $id => $definition_result) {
          unset($result[$id]['getTags()']['kernel.event_listener']);
        }
        return $result;
      });
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
