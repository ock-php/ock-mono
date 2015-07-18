<?php

namespace Drupal\renderkit\EntityDisplay;

/**
 * Entity display handler to view a specific field on all the entities.
 */
class EntityFieldDisplay extends EntityDisplayBase {

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
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  protected function buildOne($entity_type, $entity) {
    // @todo It would be faster to process multiple entities at once.
    // But also more complicated, because the core APIs suck.
    return field_view_field($entity_type, $entity, $this->fieldName, $this->display, $this->langcode);
  }
}
