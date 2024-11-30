<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Ock\Testing\Exporter\Exporter_ToYamlArray;
use PHPUnit\Framework\Assert;
use Symfony\Component\DependencyInjection\Alias;

class Snapshotter_ContainerAliases extends SnapshotterBase {

  /**
   * {@inheritdoc}
   */
  protected function getItems(): array {
    $container = \Drupal::getContainer();
    Assert::assertInstanceOf(ContainerBuilder::class, $container);
    $aliases = $container->getAliases();
    $aliases['x'] = 'y';
    ksort($aliases);
    return $aliases;
  }

  /**
   * {@inheritdoc}
   */
  protected function createExporter(): Exporter_ToYamlArray {
    return (new Exporter_ToYamlArray())
      ->withObjectGetters(Alias::class, ['isPrivate()'])
      ->withDefaultObject(new Alias('#'));
  }

}
