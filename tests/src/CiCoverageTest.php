<?php

declare(strict_types=1);

namespace Ock\Monorepo\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * Tests that CI workflows cover all packages and modules.
 *
 * Packages or modules that are not covered by a workflow must be listed in the
 * expected values below, with a reason.
 */
#[CoversNothing]
class CiCoverageTest extends TestCase {

  /**
   * Tests which packages or modules are covered by a workflow matrix.
   *
   * @param string $workflow_file
   *   Workflow file name in .github/workflows/.
   * @param string $matrix_key
   *   Key in the matrix, or in the matrix 'include' items.
   * @param string $dir
   *   Directory with one subdirectory per package or module.
   * @param list<string> $expected_not_covered
   *   Packages or modules that exist, but are not covered by the workflow.
   */
  #[DataProvider('providerWorkflowCoverage')]
  public function testWorkflowCoverage(string $workflow_file, string $matrix_key, string $dir, array $expected_not_covered): void {
    $root = dirname(__DIR__, 2);
    $existing = $this->getSubdirectoryNames($root . '/' . $dir);
    $covered = $this->getMatrixValues($root . '/.github/workflows/' . $workflow_file, $matrix_key);
    $this->assertNotEmpty($existing);
    $this->assertNotEmpty($covered);
    $this->assertSame(
      $expected_not_covered,
      array_values(array_diff($existing, $covered)),
      'Existing but not covered.',
    );
    $this->assertSame(
      [],
      array_values(array_diff($covered, $existing)),
      'Covered but not existing.',
    );
  }

  /**
   * Data provider.
   *
   * @return array<string, array{string, string, string, list<string>}>
   */
  public static function providerWorkflowCoverage(): array {
    return [
      'ci-per-package.yaml' => ['ci-per-package.yaml', 'package', 'packages', [
        // Obsolete, being removed.
        'di-discovery',
        // No tests.
        'egg',
      ]],
      'split.yaml' => ['split.yaml', 'package', 'packages', [
        // @todo Add a reason, or add to the split.
        'drupal-testing',
      ]],
      'ci-per-module.yaml' => ['ci-per-module.yaml', 'module', 'modules', [
        // No tests.
        'ock_demo',
      ]],
      'split-modules.yaml' => ['split-modules.yaml', 'module', 'modules', [
        // @todo Add reasons, or add to the split.
        'ock',
        'ock_demo',
        'renderkit',
        'service_discovery',
        'themekit',
      ]],
    ];
  }

  /**
   * Gets the names of subdirectories, sorted.
   *
   * @return list<string>
   */
  private function getSubdirectoryNames(string $dir): array {
    $paths = glob($dir . '/*', GLOB_ONLYDIR);
    $this->assertIsArray($paths);
    $names = array_map(basename(...), $paths);
    sort($names);
    return $names;
  }

  /**
   * Gets values for a key from all job matrices in a workflow, sorted.
   *
   * Supports both a plain list (matrix.KEY) and 'include' items
   * (matrix.include[].KEY).
   *
   * @return list<string>
   */
  private function getMatrixValues(string $file, string $key): array {
    $data = Yaml::parseFile($file);
    $this->assertIsArray($data);
    $this->assertIsArray($data['jobs'] ?? null);
    $values = [];
    foreach ($data['jobs'] as $job) {
      $matrix = $job['strategy']['matrix'] ?? [];
      foreach ($matrix[$key] ?? [] as $value) {
        $values[] = $value;
      }
      foreach ($matrix['include'] ?? [] as $item) {
        if (isset($item[$key])) {
          $values[] = $item[$key];
        }
      }
    }
    foreach ($values as $value) {
      $this->assertIsString($value);
    }
    /** @var list<string> $values */
    $values = array_values(array_unique($values));
    sort($values);
    return $values;
  }

}
