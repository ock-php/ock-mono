<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityImage;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\Ock\Attribute\Plugin\OckPluginFormula;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Boolean\Formula_Boolean_YesNo;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\image\Plugin\Field\FieldType\ImageItem;
use Drupal\renderkit\EntityDisplay\EntityDisplay_FieldItemsBase;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;
use Drupal\renderkit\Util\ImageFieldUtil;

class EntityImage_ImageField extends EntityDisplay_FieldItemsBase implements EntityImageInterface {

  /**
   * @param \Drupal\renderkit\Formula\Formula_EtDotFieldName $fieldNameFormula
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  #[OckPluginFormula(self::class, 'entityImageField', 'Entity image field')]
  public static function formula(
    #[GetService]
    Formula_EtDotFieldName $fieldNameFormula,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'image_field',
        Text::t('Image field'),
        $fieldNameFormula->withAllowedFieldTypes(['image']),
      )
      ->addStringParts(
        ['entity_type', 'field_name'],
        '.',
        'image_field',
      )
      ->add(
        'use_default_image',
        Text::t('Use default image as fallback'),
        Formula_Boolean_YesNo::create(TRUE),
      )
      ->construct(self::class, [
        'entity_type',
        'field_name',
        'use_default_image',
      ]);
  }

  /**
   * Constructor.
   *
   * @param string $entity_type
   * @param string $field_name
   * @param bool $useDefaultImage
   */
  public function __construct(
    string $entity_type,
    string $field_name,
    private readonly bool $useDefaultImage = TRUE,
  ) {
    parent::__construct($entity_type, $field_name);
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
