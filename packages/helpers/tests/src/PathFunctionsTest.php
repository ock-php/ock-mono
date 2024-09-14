<?php

declare(strict_types=1);

namespace Ock\Helpers\Tests;

use PHPUnit\Framework\TestCase;
use function Ock\Helpers\project_root_path;

class PathFunctionsTest extends TestCase {

  /**
   * @covers \Ock\Helpers\project_root_path()
   */
  public function testGetProjectRootPath(): void {
    $root = project_root_path();
    // The root path can differ depending how the test is run.
    // E.g. it could be '/var/www/html' when running in Docker.
    $this->assertMatchesRegularExpression('@^(/[^/]+)+$@', $root);
    $this->assertFileExists($root . '/vendor/autoload.php');
  }

}
