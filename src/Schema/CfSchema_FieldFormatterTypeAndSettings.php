<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_OptionsSchemaBase;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FormatterPluginManager;

class CfSchema_FieldFormatterTypeAndSettings extends CfSchema_Drilldown_OptionsSchemaBase {

  /**
   * @var \Drupal\Core\Field\FieldDefinitionInterface
   */
  private $fieldDefinition;

  /**
   * @var \Drupal\Core\Field\FormatterPluginManager
   */
  private $formatterPluginManager;

  /**
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $fieldDefinition
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function create(FieldDefinitionInterface $fieldDefinition) {

    return CfSchema_DrilldownVal::createArrify(
      new self(
        \Drupal::service('plugin.manager.field.formatter'),
        $fieldDefinition));
  }

  /**
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   * @param \Drupal\Core\Field\FieldDefinitionInterface $fieldDefinition
   */
  public function __construct(
    FormatterPluginManager $formatterPluginManager,
    FieldDefinitionInterface $fieldDefinition
  ) {
    $this->formatterPluginManager = $formatterPluginManager;
    $this->fieldDefinition = $fieldDefinition;

    parent::__construct(
      new CfSchema_FieldFormatterId(
        $formatterPluginManager,
        $fieldDefinition->getType()));
  }

  /**
   * @param string|int $formatterTypeName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($formatterTypeName) {

    if (!$this->idIsKnown($formatterTypeName)) {
      return NULL;
    }

    $formatter = $this->getFormatterInstance($formatterTypeName);

    if (NULL === $formatter) {
      return NULL;
    }

    return new CfSchema_FieldFormatterSettings($formatter);
  }

  /**
   * @return string
   */
  public function getIdKey() {
    return 'type';
  }

  /**
   * @return string
   */
  public function getOptionsKey() {
    return 'settings';
  }

  /**
   * @param $formatterTypeName
   *
   * @return \Drupal\Core\Field\FormatterInterface|null
   */
  private function getFormatterInstance($formatterTypeName) {

    $settings = $this->formatterPluginManager->getDefaultSettings($formatterTypeName);

    $options = [
      'field_definition' => $this->fieldDefinition,
      'configuration' => [
        'type' => $formatterTypeName,
        'settings' => $settings,
        'label' => '',
        'weight' => 0,
      ],
      'view_mode' => '_custom',
    ];

    return $this->formatterPluginManager->getInstance($options);
  }
}
