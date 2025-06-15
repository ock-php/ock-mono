<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Unit\Attribute;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\Tests\controller_attributes\Traits\ExceptionTestTrait;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Routing\Route as RoutingRoute;

/**
 * Unit test for the Route attribute class.
 *
 * This only covers the parts that are not indirectly covered by other tests,
 * mostly exceptions.
 */
#[CoversClass(Route::class)]
class RouteAttributeTest extends UnitTestCase {

  use ExceptionTestTrait;

  public function testConstructBadArguments() {
    new Route();
    new Route('');
    new Route('/hello');
    $assert_exception = fn (string $message, string $path) => $this->callAndAssertException(
      new \RuntimeException($message),
      fn () => new Route($path),
    );
    $assert_exception("Path must not end with '/'. Found '/'.", '/');
    $assert_exception("Path must not end with '/'. Found '/hello/'.", '/hello/');
    $assert_exception("Path must start with '/'. Found 'hello'.", 'hello');
  }

  public function testModifyRoute(): void {
    $attribute = new Route('/child');
    $reflector = new \ReflectionMethod(__METHOD__);

    $route = new RoutingRoute('');
    $attribute->modifyRoute($route, $reflector);
    $this->assertSame('/child', $route->getPath());

    $route = new RoutingRoute('/parent');
    $attribute->modifyRoute($route, $reflector);
    $this->assertSame('/parent/child', $route->getPath());

    $route = new RoutingRoute('/parent/');
    $this->callAndAssertException(
      new \RuntimeException("Pre-existing path must not end with '/'. Found '/parent/'."),
      fn () => $attribute->modifyRoute($route, $reflector),
    );
    $route = $this->createMock(RoutingRoute::class);
    $route->method('getPath')->willReturn('parent');
    $this->callAndAssertException(
      new \RuntimeException("Pre-existing path must start with '/'. Found 'parent'."),
      fn () => $attribute->modifyRoute($route, $reflector),
    );
  }

}
