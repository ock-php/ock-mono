<?php

namespace Drupal\renderkit8\EntityImage;

use Donquixote\Cf\Schema\Boolean\CfSchema_Boolean_YesNo;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\image\Plugin\Field\FieldType\ImageItem;
use Drupal\renderkit8\EntityDisplay\EntityDisplay_FieldItemsBase;
use Drupal\renderkit8\Schema\CfSchema_EtDotFieldName_AllowedTypes;
use Drupal\renderkit8\Util\ImageFieldUtil;

class EntityImage_ImageField extends EntityDisplay_FieldItemsBase implements EntityImageInterface {

  /**
   * @var bool
   */
  private $useDefaultImage;

  /**
   * @CfrPlugin("entityImageField", @t("Entity image field"))
   *
   * @param string $entityType
   * @param string $bundleName
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createSchema($entityType = NULL, $bundleName = NULL) {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new CfSchema_EtDotFieldName_AllowedTypes(
          $entityType,
          $bundleName,
          ['image']),
        CfSchema_Boolean_YesNo::create(TRUE),
      ],
      [
        t('Image field'),
        t('Use default image as fallback'),
      ]);
  }

  /**
   * @param string $etDotFieldName
   * @param bool $useDefaultImage
   *
   * @return self
   */
  public static function create($etDotFieldName, $useDefaultImage = TRUE) {
    list($et, $fieldName) = explode('.', $etDotFieldName . '.');
    return new self($et, $fieldName, $useDefaultImage);
  }

  /**
   * @param string $entity_type
   * @param string $field_name
   * @param bool $useDefaultImage
   */
  public function __construct($entity_type, $field_name, $useDefaultImage = TRUE) {
    parent::__construct($entity_type, $field_name);
    $this->useDefaultImage = $useDefaultImage;
  }

  /**
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *
   * @return array
   */
  protected function buildFieldItems(FieldItemListInterface $items) {

    if ($this->useDefaultImage) {
      $items = ImageFieldUtil::itemsGetItems($items);
    }

    $item = $items->first();

    if (!$item instanceof ImageItem) {
      return [];
    }

    return ImageFieldUtil::buildImageFieldItem($item);
  }
}
