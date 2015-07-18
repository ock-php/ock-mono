<?php

namespace Drupal\renderkit\EntityImage;

use Drupal\renderkit\EntityDisplay\EntityDisplayBase;

class ImageField extends EntityDisplayBase implements EntityImageInterface {

  /**
   * @var string
   */
  protected $fieldName;

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  protected function buildOne($entity_type, $entity) {
    $items = field_get_items($entity_type, $entity, $this->fieldName);
    if (!isset($items[0])) {
      return array();
    }
    return $this->buildFieldItem($items[0]);
  }

  /**
   * @param array $item
   *   Field item from an image field.
   *
   * @return array
   *
   * @see theme_image_formatter()
   */
  protected function buildFieldItem(array $item) {

    $build = array(
      '#theme' => 'image',
      '#path' => $item['uri'],
    );

    if (array_key_exists('alt', $item)) {
      $build['#alt'] = $item['alt'];
    }

    if (isset($item['attributes'])) {
      $build['#attributes'] = $item['attributes'];
    }

    if (isset($item['width']) && isset($item['height'])) {
      $build['#width'] = $item['width'];
      $build['#height'] = $item['height'];
    }

    // Do not output an empty 'title' attribute.
    if (isset($item['title']) && drupal_strlen($item['title']) > 0) {
      $build['#title'] = $item['title'];
    }

    return $build;
  }
}
