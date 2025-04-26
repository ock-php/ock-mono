<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Tests;

use Ock\ClassDiscovery\FactsIA\FactsIA_InspectFactories;
use Ock\ClassDiscovery\Inspector\FactoryInspector_Closure;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA;
use Ock\ClassDiscovery\Tests\Fixtures\Acme\Plant\MusaAcuminata;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ock\ClassDiscovery\FactsIA\FactsIA_InspectFactories
 */
class FactoryDiscoveryTest extends TestCase {

  public function testFactoryDiscovery(): void {
    $classes = ReflectionClassesIA::fromClassNames([
      self::class,
      MusaAcuminata::class,
    ]);
    $inspector = new FactoryInspector_Closure(
      static function (FactoryReflectionInterface $reflector): \Iterator {
        yield $reflector->getDebugName();
      }
    );
    $discovery = new FactsIA_InspectFactories($classes, $inspector);
    $result = iterator_to_array($discovery, false);
    $expectedSubset = [
      self::class,
      __METHOD__ . '()',
      MusaAcuminata::class,
    ];
    self::assertSame(
      $expectedSubset,
      array_values(array_intersect($result, $expectedSubset)),
    );
  }

}
