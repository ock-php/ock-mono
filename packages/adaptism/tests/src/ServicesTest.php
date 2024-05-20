<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests;

use Ock\Adaptism\AdaptismPackage;
use Ock\Adaptism\Inspector\FactoryInspector_AdapterAttribute;
use Ock\Adaptism\Inspector\FactoryInspector_SelfAdapterAttribute;
use Ock\Adaptism\Tests\Fixtures\AdaptismTestNamespace;
use Ock\Adaptism\Tests\Fixtures\FixturesUtil;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\ClassDiscovery\Discovery\DiscoveryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServicesTest extends TestCase {

  /**
   * Verifies that the common services are found in the test container.
   */
  public function testServicesExist(): void {
    $container = FixturesUtil::getContainer();

    $service = $container->get(UniversalAdapterInterface::class);
    static::assertInstanceOf(UniversalAdapterInterface::class, $service);

    $service = $container->get(DiscoveryInterface::class . ' $' . AdaptismPackage::DISCOVERY_TARGET);
    static::assertInstanceOf(DiscoveryInterface::class, $service);
  }

  public function testTaggedServicesExist(): void {
    $container = FixturesUtil::getContainer();
    static::assertInstanceOf(ContainerBuilder::class, $container);
    $taggedIds = $container->findTaggedServiceIds(AdaptismPackage::DISCOVERY_TAG_NAME);
    static::assertSame([
      FactoryInspector_AdapterAttribute::class => [[]],
      FactoryInspector_SelfAdapterAttribute::class => [[]],
      AdaptismTestNamespace::class => [[]],
    ], $taggedIds);
  }

}
