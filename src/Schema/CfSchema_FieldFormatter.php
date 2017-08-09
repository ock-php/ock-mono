<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_OptionsSchemaBase;
use Drupal\renderkit\Configurator\Configurator_FieldFormatterSettings;

class CfSchema_FieldFormatter extends CfSchema_Drilldown_OptionsSchemaBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @param string $fieldName
   * @param string $fieldTypeName
   */
  public function __construct($fieldName, $fieldTypeName) {
    parent::__construct(
      new CfSchema_FieldFormatterId($fieldTypeName));
    $this->fieldName = $fieldName;
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

    return Configurator_FieldFormatterSettings::create($this->fieldName, $formatterTypeName);
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
}
