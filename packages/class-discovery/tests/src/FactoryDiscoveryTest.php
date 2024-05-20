<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Tests;

use Ock\ClassDiscovery\Discovery\FactoryDiscovery;
use Ock\ClassDiscovery\Inspector\FactoryInspector_Closure;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA;
use Ock\ClassDiscovery\Tests\Fixtures\Acme\Plant\VenusFlyTrap;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ock\ClassDiscovery\Discovery\FactoryDiscovery
 */
class FactoryDiscoveryTest extends TestCase {

  public function testFactoryDiscovery(): void {
    $classes = ReflectionClassesIA::fromClassNames([
      self::class,
      VenusFlyTrap::class,
    ]);
    $inspector = new FactoryInspector_Closure(
      static function (FactoryReflectionInterface $reflector): \Iterator {
        yield $reflector->getDebugName();
      }
    );
    $discovery = new FactoryDiscovery($classes, $inspector);
    $result = iterator_to_array($discovery, false);
    $expectedSubset = [
      self::class,
      __METHOD__ . '()',
      VenusFlyTrap::class,
    ];
    self::assertSame(
      $expectedSubset,
      array_values(array_intersect($result, $expectedSubset)),
    );
  }

}
