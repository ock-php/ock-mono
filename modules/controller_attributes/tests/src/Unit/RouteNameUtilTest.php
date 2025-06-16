<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Unit;

use Drupal\controller_attributes\RouteNameUtil;
use Drupal\Core\Serialization\Yaml;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RouteNameUtil::class)]
class RouteNameUtilTest extends UnitTestCase {

  public function testMethodGetRouteName(): void {
    $this->assertTransformation(
      [
        'Acme\\Foo\\HelloController::goodbye' => 'acme.foo.hello.goodbye',
        'ClassInRootNamespace::examplePage' => 'class_in_root_namespace.example_page',
        'AcmeFoo\\Xyz::ABC' => 'acme_foo.xyz.abc',
      ],
      fn (string $key) => RouteNameUtil::methodGetRouteName(
        explode('::', $key),
      ),
    );
  }

  public function testClassNameGetRouteBasename(): void {
    $this->assertTransformation(
      [
        'Acme\\Foo\\HelloController' => 'acme.foo.hello',
        'Acme\\Foo\\Controller_Hello' => 'acme.foo.hello',
      ],
      RouteNameUtil::classNameGetRouteBasename(...),
    );
  }

  public function testCamelToSnake(): void {
    $this->assertTransformation(
      [
        'GREENFrog' => 'green_frog',
        'GreenFrog' => 'green_frog',
        'greenFrog' => 'green_frog',
        'GREENFROG' => 'greenfrog',
        'green_frog' => 'green_frog',
        'GREEN_FROG' => 'green_frog',
        'GreenFROG' => 'green_frog',
        'greenFRog' => 'green_f_rog',
        'greenFROG' => 'green_frog',
      ],
      RouteNameUtil::camelToSnake(...),
    );
  }

  protected function assertTransformation(array $expected, \Closure $transformation): void {
    $keys = array_keys($expected);
    $map = array_combine($keys, $keys);
    $actual = array_map($transformation, $map);
    $this->assertSame(
      "\n" . Yaml::encode($expected),
      "\n" . Yaml::encode($actual),
    );
  }

}
