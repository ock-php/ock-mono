<?php

namespace Drupal\renderkit\EntityImage;

use Drupal\renderkit\EntityDisplay\EntityDisplayBaseTrait;
use Drupal\valconfapi\Callback\Impl\ClassConstructionCallback;
use Drupal\uniplugin\UniPlugin\Configurable\HandlerFactoryConfigurableUniPlugin;
use Drupal\uniplugin\UniPlugin\ArgumentHandler\FieldSelectorArgumentHandler;

class EntityImageField implements EntityImageInterface {

  use EntityDisplayBaseTrait;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @UniPlugin(
   *   id = "entityImageField",
   *   label = "Entity image field"
   * )
   *
   * @param string $entityType
   *   (optional) Contextual parameter.
   * @param string $bundleName
   *   (optional) Contextual parameter.
   *
   * @return \Drupal\uniplugin\UniPlugin\UniPluginInterface
   */
  static function createPlugin($entityType = NULL, $bundleName = NULL) {
    $handlerFactory = ClassConstructionCallback::createFromClassName(__CLASS__);
    $argumentHandlers = array(
      'fieldName' => new FieldSelectorArgumentHandler(array('image'), $entityType, $bundleName),
    );
    return new HandlerFactoryConfigurableUniPlugin($handlerFactory, $argumentHandlers, array());
  }

  /**
   * @param string $fieldName
   *   The name of an image field, e.g. 'field_teaser_image'.
   */
  function __construct($fieldName) {
    $this->fieldName = $fieldName;
  }

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  function buildEntity($entity_type, $entity) {
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
