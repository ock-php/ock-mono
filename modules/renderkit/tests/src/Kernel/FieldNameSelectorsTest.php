<?php
declare(strict_types=1);

namespace Drupal\Tests\renderkit\Kernel;

use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;
use Drupal\renderkit\Formula\Formula_FieldName;
use Drupal\Tests\field\Kernel\FieldKernelTestBase;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Testing\ExceptionSerializationTrait;
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
    'field_test_config',
    'renderkit',
    'service_discovery',
    'ock',
    'node',
    'user',
    'layout_discovery',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installConfig(['field_test_config']);
  }

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
      'entity_test:test_bundle' => $formula->withEntityType('entity_test', 'test_bundle'),
      'entity_test:non_existing_bundle' => $formula->withEntityType('entity_test', 'non_existing_bundle'),
      'entity_test:*' => $formula->withEntityType('entity_test'),
      '*:*' => $formula,
    ];

    $fields_of_interest = [
      'user.uuid',
      'entity_test.user_id',
      'entity_test.field_test_import',
      'entity_test.field_test_import_2',
    ];
    $fields_of_interest_map = array_fill_keys($fields_of_interest, TRUE);

    foreach ($formulas_to_test as $key => $formula) {
      $options = $this->getFormulaGroupedOptions($formula);
      $filtered_options = array_filter(array_map(
        fn (array $fields) => array_intersect_key($fields, $fields_of_interest_map),
        $options,
      ));
      $this->assertAsRecorded($filtered_options, $key);
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
