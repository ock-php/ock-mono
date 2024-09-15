<?php
declare(strict_types=1);

namespace Drupal\Tests\renderkit\Kernel;

use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;
use Drupal\renderkit\Formula\Formula_FieldName;
use Drupal\Tests\field\Kernel\FieldKernelTestBase;
use Ock\Testing\ExceptionSerializationTrait;
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
    'service_discovery',
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
        $formula = $nodeFieldNameFormula->withBundle('non_existing_bundle');
        $options = $this->getFormulaGroupedOptions($formula);
        $this->assertAsRecorded($options, "$entity_type:$bundle");
      }
    }
  }

  public function testEtDotFieldName() {
    $formula = $this->getService(Formula_EtDotFieldName::class);

    $formulas_to_test = [
      'entity_test:entity_test' => $formula->withEntityType('entity_test', 'entity_test'),
      'entity_test:*' => $formula->withEntityType('entity_test'),
      '*:*' => $formula,
    ];

    foreach ($formulas_to_test as $key => $formula) {
      $options = $this->getFormulaGroupedOptions($formula);
      $this->assertAsRecorded($options, $key);
    }
  }

  /**
   * Adaptes a formula to DrupalSelect, and returns the grouped options.
   *
   * @param \Ock\Ock\Core\Formula\FormulaInterface $formula
   *   Original options formula.
   *
   * @return string[][]
   *   Grouped options, with labels cast to string.
   */
  private function getFormulaGroupedOptions(FormulaInterface $formula): array {
    if (!$formula instanceof Formula_DrupalSelectInterface) {
      $adapter = $this->getService(UniversalAdapterInterface::class);
      $formula = $adapter->adapt(
        $formula,
        Formula_DrupalSelectInterface::class,
      );
      $formula ?? self::fail(sprintf(
        'Cannot get select formula for %s',
        \get_class($formula),
      ));
    }
    $grouped_options = $formula->getGroupedOptions();
    foreach ($grouped_options as $group_label => $options_in_group) {
      foreach ($options_in_group as $value => $label) {
        $grouped_options[$group_label][$value] = (string) $label;
      }
      // For now ignore the order of options.
      // @todo Somewhere the options should be sorted alphabetically.
      ksort($grouped_options[$group_label]);
    }
    ksort($grouped_options);
    return $grouped_options;
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
