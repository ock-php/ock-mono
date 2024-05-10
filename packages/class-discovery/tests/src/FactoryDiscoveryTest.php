<?php

declare(strict_types=1);

namespace Donquixote\ClassDiscovery\Tests;

use Donquixote\ClassDiscovery\Discovery\FactoryDiscovery;
use Donquixote\ClassDiscovery\Inspector\FactoryInspector_Closure;
use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA;
use Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\VenusFlyTrap;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Donquixote\ClassDiscovery\Discovery\FactoryDiscovery
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
