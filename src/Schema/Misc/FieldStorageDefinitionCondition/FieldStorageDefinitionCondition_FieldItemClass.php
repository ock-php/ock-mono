<?php

namespace Drupal\renderkit8\Schema\Misc\FieldStorageDefinitionCondition;

use Drupal\Core\Field\FieldStorageDefinitionInterface;

class FieldStorageDefinitionCondition_FieldItemClass implements FieldStorageDefinitionConditionInterface {

  /**
   * @var string
   */
  private $classOrInterface;

  /**
   * @param string $classOrInterface
   */
  public function __construct($classOrInterface) {
    $this->classOrInterface = $classOrInterface;
  }

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storage
   *
   * @return bool
   */
  public function checkStorageDefinition(FieldStorageDefinitionInterface $storage) {

    $fieldTypeId = $storage->getType();

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    if (NULL === $fieldTypeDefinition = $ftm->getDefinition($fieldTypeId)) {
      return FALSE;
    }

    if (!isset($fieldTypeDefinition['class'])) {
      return FALSE;
    }

    $class = $fieldTypeDefinition['class'];

    if (!class_exists($class)) {
      return FALSE;
    }

    return is_a($class, $this->classOrInterface, TRUE);
  }
}
