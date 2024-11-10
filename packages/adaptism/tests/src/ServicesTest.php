<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests;

use Ock\Adaptism\Tests\Fixtures\FixturesUtil;
use Ock\Testing\Exporter\Exporter_ToYamlArray;
use Ock\Testing\RecordedTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ServicesTest extends TestCase {

  use RecordedTestTrait;

  public function testServicesAsRecorded(): void {
    $container = FixturesUtil::getContainer();
    $ids = $container->getServiceIds();
    $report = [];
    # $ids = \array_diff($ids, ['service_container']);
    \sort($ids);
    foreach ($ids as $id) {
      if (!$container->has($id)) {
        $report[$id] = '(not available)';
        continue;
      }
      try {
        $report[$id] = $container->get($id);
      }
      catch (ServiceNotFoundException $e) {
        if ($e->getId() === $id) {
          $report[$id] = '(not found)';
        }
        else {
          $report[$id] = $e;
        }
      }
      catch (\Throwable $e) {
        $report[$id] = $e;
      }
    }
    $this->assertObjectsAsRecorded(
      $report,
      arrayKeyIsDefaultClass: true,
    );
  }

  /**
   * Verifies tagged service ids.
   */
  public function testTaggedServicesAsRecorded(): void {
    $container = FixturesUtil::getContainer();
    $tag_names = $container->findTags();
    $report = [];
    foreach ($tag_names as $tag_name) {
      foreach ($container->findTaggedServiceIds($tag_name) as $service_id => $tags_info) {
        if ($tags_info === [[]]) {
          $tags_info = '[[]]';
        }
        $report[$tag_name][$service_id] = $tags_info;
      }
    }
    $this->assertAsRecorded($report);
  }

  /**
   * {@inheritdoc}
   */
  protected function createExporter(): Exporter_ToYamlArray {
    return (new Exporter_ToYamlArray())
      ->withDedicatedExporter(ContainerBuilder::class, fn (
        ContainerBuilder $builder,
      ) => ['class' => \get_class($builder)]);
  }

}
