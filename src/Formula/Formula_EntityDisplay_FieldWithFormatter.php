<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\DefaultConf\Formula_DefaultConf;
use Donquixote\ObCK\Formula\Group\Formula_Group_V2VBase;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceWithContext;
use Donquixote\ObCK\Formula\Select\Formula_Select_Fixed;
use Drupal\renderkit\EntityDisplay\EntityDisplay_FieldWithFormatter;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;

class Formula_EntityDisplay_FieldWithFormatter extends Formula_Group_V2VBase {

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
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createValFormula($entityType = NULL, $bundle = NULL) {
    return self::createConfFormula($entityType, $bundle)
      ->getValFormula();
  }

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return self
   */
  public static function createConfFormula($entityType = NULL, $bundle = NULL) {
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
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  public function getItemFormulas(): array {
    $formulas = [];

    $formulas['field'] = Formula_EtAndFieldNameAndFormatterSettings::create(
        $this->entityType,
        $this->bundleName);

    $labelFormula = Formula_Select_Fixed::createFlat(
      [
        'above' => t('Above'),
        'inline' => t('Inline'),
        'hidden' => '<' . t('Hidden') . '>',
      ]);

    $labelFormula = new Formula_DefaultConf(
      $labelFormula,
      'hidden');

    $formulas['label'] = $labelFormula;

    $formulas['processor'] = Formula_IfaceWithContext::createOptional(
      FieldDisplayProcessorInterface::class);

    return $formulas;
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
   * @throws \Donquixote\ObCK\Exception\EvaluatorException
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
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface|null $processorOrNull
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplay_FieldWithFormatter
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
