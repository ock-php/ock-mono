<?php
declare(strict_types=1);

namespace Drupal\renderkit\Util;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\Exception\ReadOnlyException;
use Drupal\field\FieldConfigInterface;
use Drupal\file\FileInterface;
use Drupal\image\Plugin\Field\FieldType\ImageItem;

final class ImageFieldUtil extends UtilBase {

  /**
   * Returns the same items, or loads default items if empty.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *
   * @return \Drupal\Core\Field\FieldItemListInterface
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function itemsGetItems(FieldItemListInterface $items): FieldItemListInterface {

    if (!$items->isEmpty()) {
      return $items;
    }

    $fieldDefinition = $items->getFieldDefinition();

    if ('image' !== $fieldDefinition->getType()) {
      return $items;
    }

    $default_image = $fieldDefinition->getSetting('default_image');

    // If we are dealing with a configurable field, look in both
    // instance-level and field-level settings.
    if (empty($default_image['uuid']) && $fieldDefinition instanceof FieldConfigInterface) {
      $default_image = $fieldDefinition->getFieldStorageDefinition()->getSetting('default_image');
    }

    if (empty($default_image['uuid'])) {
      return $items;
    }

    /** @var \Drupal\Core\Entity\EntityRepositoryInterface $er */
    $er = \Drupal::service('entity.repository');

    $file = $er->loadEntityByUuid('file', $default_image['uuid']);

    if (NULL === $file) {
      return $items;
    }

    $value = [
      'target_id' => $file->id(),
      'alt' => $default_image['alt'],
      'title' => $default_image['title'],
      'width' => $default_image['width'],
      'height' => $default_image['height'],
      'entity' => $file,
      '_loaded' => TRUE,
      '_is_default' => TRUE,
    ];

    // Clone the FieldItemList into a runtime-only object for the formatter,
    // so that the fallback image can be rendered without affecting the
    // field values in the entity being rendered.
    $items = clone $items;
    try {
      $items->setValue($value);
    }
    catch (ReadOnlyException $e) {
      throw new \RuntimeException('Field item seems to be read-only.', 0, $e);
    }

    /** @noinspection PhpDynamicFieldDeclarationInspection */
    $file->_referringItem = $items[0];

    return $items;
  }

  /**
   * @param \Drupal\image\Plugin\Field\FieldType\ImageItem $item
   *
   * @return array
   */
  public static function buildImageFieldItem(ImageItem $item): array {

    // See https://www.drupal.org/node/2901435
    // "Document field item properties with @property doc"

    $fileEntity = $item->entity;

    if (NULL === $fileEntity) {
      return [];
    }

    if (!$fileEntity instanceof FileInterface) {
      return [];
    }

    $build = [
      '#theme' => 'image',
      '#uri' => $fileEntity->getFileUri(),
    ];

    if (NULL !== $alt = $item->alt) {
      $build['#alt'] = $alt;
    }

    /* if (isset($item['attributes'])) {
      $build['#attributes'] = $item['attributes'];
    } */

    if (NULL !== $item->width && NULL !== $item->height) {
      $build['#width'] = $item->width;
      $build['#height'] = $item->height;
    }

    // Do not output an empty 'title' attribute.
    if (NULL !== ($title = $item->title) && '' !== $title) {
      $build['#title'] = $title;
    }

    return $build;
  }

}
