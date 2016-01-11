<?php

namespace Drupal\renderkit\EnumMap;

use Drupal\renderkit\Util\FieldUtil;
use Drupal\cfrapi\EnumMap\EnumMapInterface;

class EnumMap_FieldFormatterType implements EnumMapInterface {

  /**
   * @var string
   */
  private $fieldType;

  /**
   * @param string $fieldType
   */
  function __construct($fieldType) {
    $this->fieldType = $fieldType;
  }

  /**
   * @return mixed[]
   */
  function getSelectOptions() {

    $availableFormatterTypes = FieldUtil::fieldTypeGetAvailableFormatterTypes($this->fieldType);

    $options = array();
    foreach ($availableFormatterTypes as $formatterTypeName => $formatterTypeDefinition) {
      $module = $formatterTypeDefinition['module'];
      // @todo Use module label, instead of machine name.
      $options[$module][$formatterTypeName] = $formatterTypeDefinition['label'];
    }

    return $options;
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  function idGetLabel($id) {
    return FieldUtil::fieldFormatterTypeGetLabel($id);
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  function idIsKnown($id) {
    return FieldUtil::fieldFormatterTypeExists($id);
  }
}
