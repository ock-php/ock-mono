<?php

namespace Drupal\renderkit8\EntityImage;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Drupal\renderkit8\EntityDisplay\EntityDisplayBaseTrait;
use Drupal\renderkit8\Schema\CfSchema_FieldName;

class EntityImage_ImageField implements EntityImageInterface {

  use EntityDisplayBaseTrait;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @CfrPlugin("entityImageField", @t("Entity image field"))
   *
   * @param string $entityType
   * @param string $bundleName
   *
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public static function createSchema($entityType = NULL, $bundleName = NULL) {

    return CfSchema_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        new CfSchema_FieldName(
          ['image'],
          $entityType,
          $bundleName),
      ],
      [
        t('Image field'),
      ]);
  }

  /**
   * @param string $fieldName
   *   The name of an image field, e.g. 'field_teaser_image'.
   */
  public function __construct($fieldName) {
    $this->fieldName = $fieldName;
  }

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  public function buildEntity($entity_type, $entity) {

    if (empty($entity->{$this->fieldName})) {
      return [];
    }

    /** @var array[]|false $items */
    $items = field_get_items($entity_type, $entity, $this->fieldName);
    if (!isset($items[0])) {
      return [];
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

    $build = [
      '#theme' => 'image',
      '#path' => $item['uri'],
    ];

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
