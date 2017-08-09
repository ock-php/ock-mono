<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Drupal\renderkit\Util\FieldUtil;

class CfSchema_FieldFormatterId implements CfSchema_OptionsInterface {

  /**
   * @var string
   */
  private $fieldTypeName;

  /**
   * @param string $fieldTypeName
   */
  public function __construct($fieldTypeName) {
    $this->fieldTypeName = $fieldTypeName;
  }

  /**
   * @return string[][]
   */
  public function getGroupedOptions() {

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
  public function idGetLabel($formatterTypeName) {
    return FieldUtil::fieldFormatterTypeGetLabel($formatterTypeName);
  }

  /**
   * @param string $formatterTypeName
   *
   * @return bool
   */
  public function idIsKnown($formatterTypeName) {
    return FieldUtil::fieldFormatterTypeExists($formatterTypeName);
  }
}
