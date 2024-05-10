<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\AdaptismPackage;
use Donquixote\Adaptism\Inspector\FactoryInspector_AdapterAttribute;
use Donquixote\Adaptism\Inspector\FactoryInspector_SelfAdapterAttribute;
use Donquixote\Adaptism\Tests\Fixtures\AdaptismTestNamespace;
use Donquixote\Adaptism\Tests\Fixtures\FixturesUtil;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\Discovery\DiscoveryInterface;
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
