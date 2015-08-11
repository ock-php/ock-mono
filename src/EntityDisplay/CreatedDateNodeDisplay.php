<?php

namespace Drupal\renderkit\EntityDisplay;

class CreatedDateNodeDisplay extends EntityDisplayBase {

  /**
   * @var string
   */
  private $dateFormatType = 'medium';

  /**
   * @var string
   */
  private $dateFormatCustom = '';

  /**
   * @param string $type
   *   The format to use, one of:
   *   - 'short', 'medium', or 'long' (the corresponding built-in date formats).
   *   - The name of a date type defined by a module in hook_date_format_types(),
   *     if it's been assigned a format.
   *   - The machine name of an administrator-defined date format.
   */
  function setDateFormatType($type) {
    $this->dateFormatType = $type;
    $this->dateFormatCustom = '';
  }

  /**
   * @param string $format
   */
  function setDateFormatCustom($format) {
    $this->dateFormatType = 'custom';
    $this->dateFormatCustom = $format;
  }

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  function buildEntity($entity_type, $entity) {
    if ('node' !== $entity_type) {
      return array();
    }
    return array(
      '#markup' => format_date($entity->created, $this->dateFormatType, $this->dateFormatCustom)
    );
  }
}
