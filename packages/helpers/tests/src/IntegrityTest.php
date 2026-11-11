<?php

declare(strict_types = 1);

namespace Ock\Helpers\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests invariants on code level.
 */
class IntegrityTest extends TestCase {

  /**
   * Tests that the autoload.files in composer.json are up to date.
   */
  public function testFunctionIncludes(): void {
    $package_root = dirname(__DIR__, 2);
    $json = file_get_contents($package_root . '/composer.json');
    // @phpstan-ignore argument.type
    $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
    // @phpstan-ignore offsetAccess.nonOffsetAccessible, offsetAccess.nonOffsetAccessible
    $autoload_files = $data['autoload']['files'];
    $physical_files = array_map(
      fn (string $file) => 'functions/' . $file,
      // @phpstan-ignore argument.type
      preg_grep(
        '/.php$/',
        scandir($package_root . '/functions'),
      ),
    );
    sort($physical_files);
    $this->assertSame($physical_files, $autoload_files);
  }

}
