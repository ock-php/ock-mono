<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Unit;

use Drupal\controller_attributes\Controller\ControllerRouteNameTrait;
use Drupal\controller_attributes_test\Controller\HelloController;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ControllerRouteNameTrait::class)]
class ControllerRouteNameTest extends UnitTestCase {

  public function testMethodGetRouteName(): void {
    $this->assertSame(
      'controller_attributes_test.hello.hello',
      HelloController::methodGetRouteName(
        new \ReflectionMethod(HelloController::class, 'hello'),
      ),
    );
  }

  public function testMethodNameGetRouteName(): void {
    $this->assertSame(
      'controller_attributes_test.hello.hello',
      HelloController::methodNameGetRouteName('hello'),
    );
  }

  public function testGetRouteBaseName(): void {
    $this->assertSame(
      'controller_attributes_test.hello',
      HelloController::getRouteBaseName(),
    );
  }

}
