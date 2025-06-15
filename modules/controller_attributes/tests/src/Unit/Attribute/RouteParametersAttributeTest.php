<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Unit\Attribute;

use Drupal\controller_attributes\Attribute\RouteParameters;
use Drupal\Tests\controller_attributes\Traits\ExceptionTestTrait;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Routing\Route;

/**
 * Unit test for the Route attribute class.
 *
 * This only covers the parts that are not indirectly covered by other tests,
 * mostly exceptions.
 */
#[CoversClass(RouteParameters::class)]
class RouteParametersAttributeTest extends UnitTestCase {

  use ExceptionTestTrait;

  public function testConstructGoodArguments() {
    $reflector = new \ReflectionMethod(__METHOD__);
    $assert_parameters_ok = function (array $parameters, array $values = NULL) use ($reflector) {
      $route = new Route('/path');
      $attribute = new RouteParameters($values ?? $parameters);
      $attribute->modifyRoute($route, $reflector);
      $this->assertSame($parameters, $route->getOption('parameters'));
    };
    $assert_parameters_ok(['arg' => ['type' => 'x']]);
    $assert_parameters_ok(['arg' => []]);
    $assert_parameters_ok([]);
    $assert_parameters_ok(['arg' => ['type' => 'y']], ['arg' => 'y']);
  }

  public function testConstructBadArguments() {
    $assert_exception = fn (string $message, array $values) => $this->callAndAssertException(
      new \RuntimeException($message),
      fn () => new RouteParameters($values),
    );
    $assert_exception("Parameter array must have only one key, 'type'.", ['arg' => ['x' => 'X']]);
    $assert_exception('Parameter type must be a string.', ['arg' => ['type' => 5]]);
    $assert_exception('Parameter must be an array or a string.', ['arg' => 5]);
  }

}
