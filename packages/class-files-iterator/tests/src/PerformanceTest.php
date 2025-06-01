<?php

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\NamespaceDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class PerformanceTest extends TestCase {

  public function testPerformance(): void {
    $nsdir = NamespaceDirectory::fromKnownClass(TestCase::class)->requireParentN(1);
    $dir = $nsdir->getDirectory();
    $tick = $this->stopwatch();
    $dts = [];

    for ($i = 0; $i < 10; ++$i) {
      $it = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
      $itt = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
      $itt_files = iterator_to_array($itt);
      $dts['itt'][] = $tick();

      $finder = Finder::create()->files()->name('*.php')->in($dir);
      $n_finder = iterator_count($finder);
      $dts['finder'][] = $tick();

      $n_nsdir = iterator_count($nsdir);
      $dts['nsdir'][] = $tick();
    }

    $itt_php_files = preg_grep('#\.php$#', $itt_files);
    assert($itt_php_files !== false);
    $n_itt = count($itt_php_files);
    $this->assertGreaterThan(300, $n_itt);
    $this->assertLessThan(3000, $n_itt);
    $this->assertSame($n_itt, $n_finder);
    $this->assertSame($n_itt, $n_nsdir);

    $dt_counts = array_map(count(...), $dts);
    $this->assertSame([], array_diff($dt_counts, [10]));

    // For a deterministic operation, the fastest time is usually the most
    // reproducible and comparable.
    // @phpstan-ignore argument.type
    $dt_mins = array_map(min(...), $dts);

    $dt_ref = $dt_mins['itt'];
    $this->assertEqualsWithDeltaFactor(5.4, $dt_mins['finder'] / $dt_ref, .1);
    $this->assertEqualsWithDeltaFactor(3.17, $dt_mins['nsdir'] / $dt_ref, .1);
  }

  protected function assertEqualsWithDeltaFactor(float $expected, float $actual, float $delta): void {
    $this->assertLessThan($expected * (1 + $delta), $actual);
    $this->assertGreaterThan($expected / (1 + $delta), $actual);
  }

  /**
   * Starts a stopwatch timer.
   *
   * @return \Closure(): float
   *   Function which returns milliseconds since previous call.
   */
  protected function stopwatch(): \Closure {
    $t0 = hrtime(true);
    return function () use (&$t0) {
      $dt = hrtime(true) - $t0;
      $t0 += $dt;
      return $dt * .000001;
    };
  }

}
