<?php

namespace Drupal\renderkit\EntityDisplay;

use Drupal\renderkit\Helper\EntityTypeFieldDisplayHelper;

/**
 * Entity display handler to view a specific field on all the entities.
 */
class EntityFieldDisplay extends EntitiesDisplaysBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var array
   */
  private $display;

  /**
   * @var string|null
   */
  private $langcode;

  /**
   * @param string $field_name
   * @param array $display
   * @param string $langcode
   */
  function __construct($field_name, array $display = array(), $langcode = NULL) {
    $this->fieldName = $field_name;
    $this->display = $display + array('label' => 'hidden');
    $this->langcode = $langcode;
  }

  /**
   * Sets the field formatter.
   *
   * @param string $name
   *   The machine name of the field formatter.
   * @param array $settings
   *   The formatter settings.
   *
   * @return $this
   */
  function setFormatter($name, array $settings = NULL) {
    $this->display['type'] = $name;
    if (isset($settings)) {
      $this->display['settings'] = $settings;
    }
    return $this;
  }

  /**
   * @param string $label_position
   *   The default implementation supports 'above', 'inline' and 'hidden'.
   *   Default in core is 'above', but default here is 'hidden'.
   */
  function setLabelPosition($label_position) {
    $this->display['label'] = $label_position;
  }

  /**
   * @param string $entity_type
   * @param array $entities
   *
   * @return array
   * @throws \EntityMalformedException
   */
  function buildMultiple($entity_type, array $entities) {
    $helper = EntityTypeFieldDisplayHelper::create($entity_type, $this->fieldName, $this->display, $this->langcode);
    return $helper->buildMultipleByDelta($entities);
  }

}
