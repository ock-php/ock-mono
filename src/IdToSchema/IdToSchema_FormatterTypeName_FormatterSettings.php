<?php

namespace Drupal\renderkit8\IdToSchema;

use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FormatterPluginManager;
use Drupal\renderkit8\Schema\CfSchema_FieldFormatterId;
use Drupal\renderkit8\Schema\CfSchema_FieldFormatterSettings;

class IdToSchema_FormatterTypeName_FormatterSettings implements IdToSchemaInterface {

  /**
   * @var \Drupal\Core\Field\FieldDefinitionInterface
   */
  private $fieldDefinition;

  /**
   * @var \Drupal\Core\Field\FormatterPluginManager
   */
  private $formatterPluginManager;

  /**
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   * @param \Drupal\Core\Field\FieldDefinitionInterface $fieldDefinition
   *
   * @return \Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface
   */
  public static function createDrilldownValSchema(
    FormatterPluginManager $formatterPluginManager,
    FieldDefinitionInterface $fieldDefinition
  ) {
    return CfSchema_DrilldownVal::createArrify(
      self::createDrilldownSchema(
        $formatterPluginManager,
        $fieldDefinition));
  }

  /**
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   * @param \Drupal\Core\Field\FieldDefinitionInterface $fieldDefinition
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function createDrilldownSchema(
    FormatterPluginManager $formatterPluginManager,
    FieldDefinitionInterface $fieldDefinition
  ) {

    return CfSchema_Drilldown::create(
      new CfSchema_FieldFormatterId(
        $formatterPluginManager,
        $fieldDefinition->getType()),
      new self(
        $formatterPluginManager,
        $fieldDefinition))
      ->withKeys('type', 'settings');
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
  }

  /**
   * @param string|int $formatterTypeName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($formatterTypeName) {

    $formatter = $this->getFormatterInstance($formatterTypeName);

    if (NULL === $formatter) {
      return NULL;
    }

    return new CfSchema_FieldFormatterSettings($formatter);
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
