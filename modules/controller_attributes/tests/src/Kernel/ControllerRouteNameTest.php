<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Kernel;

use Drupal\controller_attributes\ClassRouteHelper;
use Drupal\controller_attributes_test\Controller\HelloController;
use Drupal\KernelTests\KernelTestBase;

class ControllerRouteNameTest extends KernelTestBase {

  protected static $modules = [
    'controller_attributes_test',
  ];

  public function testHelloControllerRouteName(): void {
    $builder = ClassRouteHelper::fromClassName(
      HelloController::class,
      [],
      'hello',
    );
    $this->assertSame(
      [
        'name' => 'controller_attributes_test.hello.hello',
        'url' => '/controller-attributes-test/hello',
        'link' => '<a href="/controller-attributes-test/hello">Hello</a>',
      ],
      [
        'name' => $builder->routeName(),
        'url' => $builder->url()->toString(),
        'link' => $builder->link('Hello')->toString()->getGeneratedLink(),
      ],
    );
  }

}
