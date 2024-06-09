<?php

declare(strict_types=1);

namespace Ock\Helpers\Tests;

use PHPUnit\Framework\TestCase;
use function Ock\Helpers\is_valid_identifier;
use function Ock\Helpers\is_valid_qcn;

class StringFunctionsTest extends TestCase {

  /**
   * @covers \Ock\Helpers\is_valid_identifier()
   */
  public function testIsValidIdentifier(): void {
    $groups = [
      [
        'xy\\z',
        '0abc',
        '',
        '1',
        '\\x',
        'x\\',
        ',',
      ],
      [
        'abc',
        'A123',
      ],
    ];
    foreach ($groups as $is_identifier => $strings) {
      foreach ($strings as $string) {
        self::assertSame((bool) $is_identifier, is_valid_identifier($string));
      }
    }
  }

  /**
   * @covers \Ock\Helpers\is_valid_qcn()
   */
  public function testIsValidQcn(): void {
    $groups = [
      [
        'xy\\0z',
        '0abc',
        '',
        '1',
        '\\x',
        'x\\',
      ],
      [
        'abc',
        'A123',
        'A\\B',
      ],
    ];
    foreach ($groups as $is_identifier => $strings) {
      foreach ($strings as $string) {
        self::assertSame((bool) $is_identifier, is_valid_qcn($string));
      }
    }
  }

}
