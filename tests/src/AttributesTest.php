<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests;

use Donquixote\Ock\Annotation\OckPlugin;
use Donquixote\Ock\Tests\Fixture\IntCondition\IntCondition_Odd;
use PHPStan\BetterReflection\BetterReflection;
use PHPUnit\Framework\TestCase;

class AttributesTest extends TestCase {

  /**
   * Tests attributes reading.
   */
  public function testAttributes() {
    $rc = (new BetterReflection())
      ->classReflector()
      ->reflect(IntCondition_Odd::class);
    $instances = [];
    foreach ($rc->getAttributes() as $attribute) {
      $instances[] = $attribute->newInstance();
    }
    self::assertEquals(
      [
        new OckPlugin('odd', 'Number is odd'),
      ],
      $instances);
  }

}
