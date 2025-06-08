<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Unit;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\ControllerAttributesRouteProvider;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Tests\controller_attributes\Traits\ExceptionTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ControllerAttributesRouteProvider::class)]
class ControllerAttributesRouteProviderUnitTest extends TestCase {

  use ExceptionTestTrait;

  public function testGetRoutesForClass(): void {
    $provider = new ControllerAttributesRouteProvider(
      $this->createMock(ModuleHandlerInterface::class),
      $this->createMock(ModuleExtensionList::class),
    );
    $fn = fn (string $class) => (new \ReflectionMethod($provider, 'getRoutesForClass'))
      ->invoke($provider, $class);

    $class = get_class(new class() {
      #[Route()]
      public function badPath(): array {
        return [];
      }
    });

    $this->callAndAssertException(\RuntimeException::class, fn () => $fn($class));
  }

}
