<?php
declare(strict_types=1);

namespace Drupal\Tests\renderkit\Kernel;

use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;
use Drupal\renderkit\Formula\Formula_FieldName;
use Drupal\Tests\field\Kernel\FieldKernelTestBase;
use Drupal\Tests\themekit\Traits\ExceptionSerializationTrait;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Testing\RecordedTestTrait;

/**
 * @see \Drupal\KernelTests\Core\Render\Element\RenderElementTypesTest
 */
class FieldNameSelectorsTest extends FieldKernelTestBase {

  use ExceptionSerializationTrait;

  use RecordedTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'system',
    'field',
    'text',
    'entity_test',
    'field_test',
    'renderkit',
    'ock',
    'node',
    'user',
    'layout_discovery',
  ];

  public function testFieldName(): void {
    /** @var \Closure(string): Formula_FieldName $fieldNameFormulaLookup */
    $fieldNameFormulaLookup = $this->container->get(Formula_FieldName::LOOKUP_SERVICE_ID);
    $nodeFieldNameFormula = $fieldNameFormulaLookup('node');

    foreach ([
      'node' => ['non_existing_bundle', 'default'],
      'entity_text' => ['entity_test', 'non_existing_bundle'],
    ] as $entity_type => $bundles) {
      foreach ($bundles as $bundle) {
        $this->assertFormulaGroupedOptions(
          $nodeFieldNameFormula->withBundle('non_existing_bundle'),
          "$entity_type:$bundle",
        );
      }
    }
  }

  public function testEtDotFieldName() {
    $formula = $this->getService(Formula_EtDotFieldName::class);

    $this->assertFormulaGroupedOptions(
      $formula->withEntityType('entity_test', 'entity_test'),
      'entity_test:entity_test',
    );

    $this->assertFormulaGroupedOptions(
      $formula->withEntityType('entity_test'),
      'entity_test:*',
    );

    $this->assertFormulaGroupedOptions(
      $formula,
      '*:*',
    );
  }

  /**
   * @param \Ock\Ock\Core\Formula\FormulaInterface $formula
   * @param string $key
   */
  private function assertFormulaGroupedOptions(FormulaInterface $formula, string $key = NULL): void {
    if (!$formula instanceof Formula_DrupalSelectInterface) {
      $adapter = $this->getService(UniversalAdapterInterface::class);
      $formula = $adapter->adapt(
        $formula,
        Formula_DrupalSelectInterface::class,
      )
        ?? self::fail(sprintf(
          'Cannot get select formula for %s',
          \get_class($formula),
        ));
    }

    $this->assertGroupedOptions(
      $formula->getGroupedOptions(),
      $key,
    );
  }

  /**
   * @param (string|\Drupal\Component\Render\MarkupInterface)[][] $actualWithMarkup
   * @param string $key
   */
  private function assertGroupedOptions(array $actualWithMarkup, string $key = NULL): void {
    $actual = [];
    foreach ($actualWithMarkup as $groupLabel => $groupOptions) {
      foreach ($groupOptions as $k => $label) {
        $actual[$groupLabel][$k] = (string) $label;
      }
    }
    static::assertAsRecorded($actual, $key);
  }

  /**
   * @template T of object
   *
   * @param class-string<T> $class
   *
   * @return T&object
   */
  private function getService(string $class): object {
    $service = $this->container->get($class);
    self::assertInstanceOf($class, $service);
    return $service;
  }

}
