<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Drupal\Core\Field\FormatterPluginManager;

class CfSchema_FieldFormatterId implements CfSchema_OptionsInterface {

  /**
   * @var \Drupal\Core\Field\FormatterPluginManager
   */
  private $formatterPluginManager;

  /**
   * @var string
   */
  private $fieldTypeName;

  /**
   * @param string $fieldTypeName
   *
   * @return self
   */
  public static function create($fieldTypeName) {

    /* @var \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager */
    $formatterPluginManager = \Drupal::service('plugin.manager.field.formatter');

    return new self($formatterPluginManager, $fieldTypeName);
  }

  /**
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   * @param string $fieldTypeName
   */
  public function __construct(FormatterPluginManager $formatterPluginManager, $fieldTypeName) {
    $this->formatterPluginManager = $formatterPluginManager;
    $this->fieldTypeName = $fieldTypeName;
  }

  /**
   * @return string[][]
   */
  public function getGroupedOptions() {

    $options = $this->formatterPluginManager->getOptions($this->fieldTypeName);

    foreach ($options as $type => $label) {
      # $options[$type] = (string)$label;
      $options[$type] = $type;
    }

    return ['' => $options];
  }

  /**
   * @param string $formatterTypeName
   *
   * @return string|null
   */
  public function idGetLabel($formatterTypeName) {

    if (NULL === $definition = $this->idGetDefinition($formatterTypeName)) {
      return NULL;
    }

    return $definition['label'];
  }

  /**
   * @param string $formatterTypeName
   *
   * @return bool
   */
  public function idIsKnown($formatterTypeName) {

    return NULL !== $this->idGetDefinition($formatterTypeName);
  }

  /**
   * @param string $formatterTypeName
   *
   * @return array|null
   */
  private function idGetDefinition($formatterTypeName) {

    $definition = $this->formatterPluginManager->getDefinition(
      $formatterTypeName,
      FALSE);

    if (NULL === $definition) {
      return NULL;
    }

    if (!in_array($this->fieldTypeName, $definition['field_types'], TRUE)) {
      return NULL;
    }

    return $definition;
  }
}
