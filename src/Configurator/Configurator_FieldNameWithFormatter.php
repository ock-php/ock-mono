<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator;
use Drupal\cfrfamily\Configurator\Composite\Configurator_IdConfBase;
use Drupal\renderkit\Util\FieldUtil;

class Configurator_FieldNameWithFormatter extends Configurator_IdConfBase {

  /**
   * @var null|string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param string $entityType
   * @param string $bundleName
   */
  public function __construct($entityType = NULL, $bundleName = NULL) {
    parent::__construct(TRUE, $this, 'field', 'display');
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
  }

  /**
   * @param string $fieldName
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  protected function idGetConfigurator($fieldName) {

    $fieldInfo = field_info_field($fieldName);

    if (!isset($fieldInfo['type'])) {
      if (!isset($fieldInfo)) {
        return new BrokenConfigurator($this, get_defined_vars(), 'Unknown field.');
      }
      return new BrokenConfigurator($this, get_defined_vars(), 'Field without type.');
    }

    return new Configurator_FieldFormatter($fieldName, $fieldInfo['type']);
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {
    return FieldUtil::fieldTypesGetFieldNameOptions(NULL, $this->entityType, $this->bundleName);
  }

  /**
   * @param string $fieldName
   *
   * @return string
   */
  protected function idGetLabel($fieldName) {
    return FieldUtil::fieldnameEtBundleGetLabel($fieldName, $this->entityType, $this->bundleName);
  }
}
