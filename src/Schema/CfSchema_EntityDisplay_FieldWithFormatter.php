<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Group\CfSchema_Group_V2VBase;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\Options\CfSchema_Options_Fixed;
use Drupal\renderkit\EntityDisplay\EntityDisplay_FieldWithFormatter;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;

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
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  public function getItemSchemas() {
    $schemas = [];

    $schemas['field'] = new CfSchema_FieldNameWithFormatter(
      $this->entityType,
      $this->bundleName);

    $schemas['label'] = CfSchema_Options_Fixed::createFlat(
      [
        'above' => t('Above'),
        'inline' => t('Inline'),
        'hidden' => '<' . t('Hidden') . '>',
      ]);

    $schemas['processor'] = CfSchema_IfaceWithContext::createOptional(
      FieldDisplayProcessorInterface::class);

    return $schemas;
  }

  /**
   * @return string[]
   */
  public function getLabels() {
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
  public function itemsPhpGetPhp(array $itemsPhp) {

    return '\\' . self::class . '::createEntityDisplay('
      . "\n" . $itemsPhp['field'] . ','
      . "\n" . $itemsPhp['label'] . ','
      . "\n" . $itemsPhp['processor'] . ')';
  }

  /**
   * @param array $fieldSettings
   * @param string $labelDisplay
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface|null $processorOrNull
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplay_FieldWithFormatter
   */
  public static function createEntityDisplay(
    array $fieldSettings,
    $labelDisplay,
    FieldDisplayProcessorInterface $processorOrNull = NULL
  ) {

    $fieldName = $fieldSettings['field'];

    $fieldDisplay = $fieldSettings['display'];
    $fieldDisplay['label'] = $labelDisplay;

    return new EntityDisplay_FieldWithFormatter(
      $fieldName,
      $fieldDisplay,
      $processorOrNull);

  }
}
