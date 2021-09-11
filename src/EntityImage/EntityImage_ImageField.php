<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityImage;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Boolean\Formula_Boolean_YesNo;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal_Callback;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\image\Plugin\Field\FieldType\ImageItem;
use Drupal\renderkit\EntityDisplay\EntityDisplay_FieldItemsBase;
use Drupal\renderkit\Formula\Formula_EtDotFieldName_AllowedTypes;
use Drupal\renderkit\Util\ImageFieldUtil;

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
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createFormula(string $entityType = NULL, string $bundleName = NULL): FormulaInterface {

    return Formula_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new Formula_EtDotFieldName_AllowedTypes(
          $entityType,
          $bundleName,
          ['image']),
        Formula_Boolean_YesNo::create(TRUE),
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
  public static function create(string $etDotFieldName, bool $useDefaultImage = TRUE): self {
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
   * @param \Drupal\Core\Field\FieldItemListInterface $fieldItemList
   *
   * @return array
   */
  protected function buildFieldItems(FieldItemListInterface $fieldItemList): array {

    if ($this->useDefaultImage) {
      try {
        $fieldItemList = ImageFieldUtil::itemsGetItems($fieldItemList);
      }
      catch (EntityStorageException $e) {
        // @todo Log this.
        unset($e);
        return [];
      }
    }

    try {
      $item = $fieldItemList->first();
    }
    catch (MissingDataException $e) {
      unset($e);
      return [];
    }

    if (!$item instanceof ImageItem) {
      return [];
    }

    return ImageFieldUtil::buildImageFieldItem($item);
  }
}
