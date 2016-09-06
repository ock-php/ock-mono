<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrfamily\Configurator\Composite\Configurator_IdConfBase;
use Drupal\renderkit\Util\FieldUtil;

class Configurator_FieldFormatter extends Configurator_IdConfBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var string
   */
  private $fieldTypeName;

  /**
   * @param string $fieldName
   * @param string $fieldTypeName
   */
  public function __construct($fieldName, $fieldTypeName) {
    parent::__construct(TRUE, $this, 'type', 'settings');
    $this->fieldName = $fieldName;
    $this->fieldTypeName = $fieldTypeName;
  }

  /**
   * @param string $formatterTypeName
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  protected function idGetConfigurator($formatterTypeName) {
    return Configurator_FieldFormatterSettings::create($this->fieldName, $formatterTypeName);
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {

    $availableFormatterTypes = FieldUtil::fieldTypeGetAvailableFormatterTypes($this->fieldTypeName);

    $options = [];
    foreach ($availableFormatterTypes as $formatterTypeName => $formatterTypeDefinition) {
      $module = $formatterTypeDefinition['module'];
      // @todo Use module label, instead of machine name.
      $options[$module][$formatterTypeName] = $formatterTypeDefinition['label'];
    }

    return $options;
  }

  /**
   * @param string $formatterTypeName
   *
   * @return string
   */
  protected function idGetLabel($formatterTypeName) {
    return FieldUtil::fieldFormatterTypeGetLabel($formatterTypeName);
  }
}
