<?php

declare(strict_types = 1);

namespace src\Unit;

use Drupal\controller_attributes\PluginDeriver\PluginDeriverBase;
use PHPUnit\Framework\TestCase;

class PluginDeriverBaseTest extends TestCase {

  public function testGetDerivativeDefinitions(): void {
    $deriver = new class () extends PluginDeriverBase {
      protected function buildDerivativeDefinitions(): array {
        return [
          'ab' => ['a' => 'A', 'b' => 'B'],
          'xy' => ['x' => 'X', 'y' => 'Y'],
        ];
      }
    };
    $this->assertSame(
      ['a' => 'A', 'b' => 'B'],
      $deriver->getDerivativeDefinition('ab', ['?']),
    );
  }

}
