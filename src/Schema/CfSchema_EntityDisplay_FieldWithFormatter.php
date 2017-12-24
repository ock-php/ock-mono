<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\DefaultConf\CfSchema_DefaultConf;
use Donquixote\Cf\Schema\Group\CfSchema_Group_V2VBase;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\Select\CfSchema_Select_Fixed;
use Drupal\renderkit8\EntityDisplay\EntityDisplay_FieldWithFormatter;
use Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface;

class CfSchema_EntityDisplay_FieldWithFormatter extends CfSchema_Group_V2VBase {

  /**
   * @var null|string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createValSchema($entityType = NULL, $bundle = NULL) {
    return self::createConfSchema($entityType, $bundle)
      ->getValSchema();
  }

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return self
   */
  public static function createConfSchema($entityType = NULL, $bundle = NULL) {
    return new self($entityType, $bundle);
  }

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   */
  public function __construct($entityType = NULL, $bundle = NULL) {
    $this->entityType = $entityType;
    $this->bundleName = $bundle;
  }

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface[]
   */
  public function getItemSchemas(): array {
    $schemas = [];

    $schemas['field'] = CfSchema_EtAndFieldNameAndFormatterSettings::create(
        $this->entityType,
        $this->bundleName);

    $labelSchema = CfSchema_Select_Fixed::createFlat(
      [
        'above' => t('Above'),
        'inline' => t('Inline'),
        'hidden' => '<' . t('Hidden') . '>',
      ]);

    $labelSchema = new CfSchema_DefaultConf(
      $labelSchema,
      'hidden');

    $schemas['label'] = $labelSchema;

    $schemas['processor'] = CfSchema_IfaceWithContext::createOptional(
      FieldDisplayProcessorInterface::class);

    return $schemas;
  }

  /**
   * @return string[]
   */
  public function getLabels(): array {
    return [
      'field' => t('Field'),
      'label' => t('Label display'),
      'processor' => t('Field display processor'),
    ];
  }

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values) {

    return self::createEntityDisplay(
      $values['field'],
      $values['label'],
      $values['processor']);
  }

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {

    return '\\' . self::class . '::createEntityDisplay('
      . "\n" . $itemsPhp['field'] . ','
      . "\n" . $itemsPhp['label'] . ','
      . "\n" . $itemsPhp['processor'] . ')';
  }

  /**
   * @param array $fieldSettings
   * @param string $labelDisplay
   * @param \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface|null $processorOrNull
   *
   * @return \Drupal\renderkit8\EntityDisplay\EntityDisplay_FieldWithFormatter
   */
  public static function createEntityDisplay(
    array $fieldSettings,
    $labelDisplay,
    FieldDisplayProcessorInterface $processorOrNull = NULL
  ) {

    $et = $fieldSettings['entity_type'];
    $fieldAndFormatter = $fieldSettings['field_and_formatter'];
    $fieldName = $fieldAndFormatter['field'];

    $fieldDisplay = $fieldAndFormatter['display'];
    $fieldDisplay['label'] = $labelDisplay;

    return EntityDisplay_FieldWithFormatter::create(
      $et,
      $fieldName,
      $fieldDisplay,
      $processorOrNull);

  }
}
