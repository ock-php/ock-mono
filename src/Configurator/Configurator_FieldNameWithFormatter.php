<?php

namespace Drupal\renderkit\Configurator;

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
      return NULL;
    }

    return new Configurator_FieldFormatter($fieldName, $fieldInfo['type']);
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  protected function idGetOptionsFormLabel($id) {
    return t('Formatter');
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
